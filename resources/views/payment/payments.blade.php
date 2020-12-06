<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight pt-2">
                {{ __('Payments') }}
            </h1>
            </div>
            <div class="col">
            <a href="payments/create" class="btn btn-outline-success shadow rounded pull-right">New Payments</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Amount recived</th>
                    <th scope="col">Percentage</th>
                    <th scope="col">Cost of payment</th>
                    <th scope="col">Book name</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                  @foreach($payments as $payment)
                    <th scope="row">{{$payment->id}}</th>
                    <td>{{$payment->payment_date}}</td>
                    
                    <td>{{$payment->amount}}</td>
                    <td>{{$payment->percentage}}%</td>
                    <td>{{$payment->payment_cost}}</td>
                    @foreach($books as $book)
                      @if($payment->book_id==$book->id)
                        <td>{{$book->book_name}}</td>
                      @endif
                    @endforeach
                  
                    <td>
                      <a href="{{ URL::to('payments/' . $payment->id . '/edit') }}" type="button" class="btn btn-warning btn-sm shadow rounded  "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                      <!-- <button type="button" class="btn btn-primary btn-sm shadow  "><i class="fa fa-eye" aria-hidden="true"></i></button> -->
                      <a type="button" class="btn btn-danger btn-sm shadow rounded  btn-payment " id="{{$payment->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                      
                    </td>
                  </tr>
                  @endforeach
                  
                </tbody>
              </table>
                <!-- <x-jet-welcome /> -->
            </div>
        </div>
    </div>
</x-app-layout>