<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Payment;

use Illuminate\Http\Request;
use Validator;
use File;
use Image;
use Auth;
use View;
use Storage;
use DB;

class PaymentManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $currentUser = Auth::user()->id; 
        $payments   =Payment::where('author_id',$currentUser)->paginate(15);
        $books      =Book::where('author_id',$currentUser)->get();
        return view('payment.payments', compact('payments','books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user()->id;
        $books      =Book::where('author_id',$currentUser)->get();
        return view('payment.addpayment', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user()->id;
        $validator = Validator::make(
            $request->all(),
            [
                'payment_date'             => 'required|max:255',
                'amount'             => 'required',
                'book_id'                  =>'required',
                'payment_percentage' =>'required|max:100|min:0',
                'count'             =>'required|min:1',
            ],
            [
                'payment_date.required'                    => 'Payment Date is required',
                'amount.required'                    => 'Book Amount is required',
                'book_id.required'                    => 'Book Name is required',
                'payment_percentage.required'                    => 'Payment Percentage is required',
                'payment_percentage.max'                    => 'Payment Percentage maximum is 100%',
                'payment_percentage.min'                    => 'Payment Percentage maximum is 0%',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $amount=$request->input('amount');
        $percentage=(int)$request->input('payment_percentage');
        $count=(int)$request->input('count');
        $cost=$amount*$percentage/100;
       
        
           
        for ($x = 0; $x < $count; $x++) {
            $payment = Payment::create([
                'payment_date'                       => $request->input('payment_date'),
                'amount'                             => $amount,
                'payment_cost'                       => $cost,
                'author_id'                          => $currentUser,
                'book_id'                            => $request->input('book_id'),
                'percentage'                         =>$percentage,
                ]);
            $payment->save();
        }

        return redirect('/payments')->with('success', 'Payment detail is Added !!!');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment   =Payment::find($id);
        $currentUser = Auth::user()->id;
        $books      =Book::where('author_id',$currentUser)->get();
        return view('payment.editpayment', compact('payment','books'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user()->id;
        $validator = Validator::make(
            $request->all(),
            [
                'payment_date'             => 'required|max:255',
                'amount'             => 'required',
                'book_id'                  =>'required',
                'payment_percentage' =>'required|max:100|min:0',
            ],
            [
                'payment_date.required'                    => 'Payment Date is required',
                'amount.required'                    => 'Book Amount is required',
                'book_id.required'                    => 'Book Name is required',
                'payment_percentage.required'                    => 'Payment Percentage is required',
                'payment_percentage.max'                    => 'Payment Percentage maximum is 100%',
                'payment_percentage.min'                    => 'Payment Percentage maximum is 0%',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $amount=$request->input('amount');
        $percentage=(int)$request->input('payment_percentage');
        $cost=$amount*$percentage/100;

        $payment = Payment::find($id);
        
        $payment->payment_date               =$request->input('payment_date');
        $payment->amount                     = $amount;
        $payment->payment_cost                        = $cost;
        $payment->author_id                          = $currentUser;
        $payment->book_id                           = $request->input('book_id');
        $payment->percentage                         =$percentage;
        
        $payment->save();

        return redirect('/payments')->with('success', 'payment detail is Updated !!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }
    public function deleteRequest(Request $request){
        
        $payment = Payment::find($request->id);
        if ($payment->id) {
            $payment->delete();
            
        }
        $response = array(
            'status' => 'success',
            'msg' => $request->message,
        );
        return response()->json($response); 
    }
    public function getCost(Request $request){

        $book = Book::Find($request->id);

        return response()->json($book->cost); 
    }
    public function total(Request $request){
        $currentUser = Auth::user()->id;
        $amount = Payment::Where('author_id',$currentUser)->sum('amount');
        $payment_cost = Payment::Where('author_id',$currentUser)->sum('payment_cost');
        $response = array(
            'amount' => $amount,
            'payment_cost' => $payment_cost,
        );
    
        return response($response); 
    }
    public function bookVice(Request $request){
        $currentUser = Auth::user()->id;
        $books=DB::table('payments')->where('author_id',$currentUser)
        ->select(DB::raw('sum(payment_cost) as cost'),DB::raw('sum(amount) as payment'),DB::raw('book_id as id') )
        ->groupBy(DB::raw('book_id') )
        ->get();

        foreach($books as $book){
            $x=Book::Find($book->id);
            $book->id= $x->book_name;
        }
        
        return response($books); 
    }
    public function paymentHistory(Request $request){
        $currentUser = Auth::user()->id;
        $payment = Payment::Where('author_id',$currentUser)->get();
        
        return response($payment); 
    }
    public function search(Request $request){
        $currentUser = Auth::user()->id;
       
        $payment=Payment::Where('author_id',$currentUser)->whereBetween('payment_date', [$request->start, $request->end])->get();
        return response($payment); 
    }
}
