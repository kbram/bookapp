<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::fallback('App\Http\Controllers\HomeController@index');

Route::get('/', function () {
    if(Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth/login');
});

Route::get('reset', function () {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    //Artisan::call('storage:link');
});
Route::get('migrate', function () {
    Artisan::call('migrate:fresh');  
    return "migrated"; 
});

Route::get('seed', function () {
    Artisan::call('db:seed');   
});
Route::resource('books', 'App\Http\Controllers\BookManagementController');

Route::post('book/delete', 'App\Http\Controllers\BookManagementController@deleteRequest');

Route::resource('payments', 'App\Http\Controllers\PaymentManagementController', ['except' => ['index','show','delete']]);
Route::post('payment/delete', 'App\Http\Controllers\PaymentManagementController@deleteRequest');
Route::get('payment/getbookcost', 'App\Http\Controllers\PaymentManagementController@getCost');
Route::get('payment/total', 'App\Http\Controllers\PaymentManagementController@total');
Route::get('payment/bookvice', 'App\Http\Controllers\PaymentManagementController@bookVice');
Route::get('payment/paymenthistory', 'App\Http\Controllers\PaymentManagementController@paymentHistory');
Route::get('payment/serch', 'App\Http\Controllers\PaymentManagementController@search');

Route::get('/home', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return App::call('App\Http\Controllers\HomeController@dash');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/books', function () {
    return App::call('App\Http\Controllers\BookManagementController@index');
})->name('books');

Route::middleware(['auth:sanctum', 'verified'])->get('/payments', function () {
    return App::call('App\Http\Controllers\PaymentManagementController@index');
})->name('payments');

Route::view('/livewire', 'livewire');