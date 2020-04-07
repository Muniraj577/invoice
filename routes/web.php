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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'ProductController@app');
Route::get('/findPrice', 'InvoiceController@findPrice');
Route::post('/get-invoices', 'InvoiceController@getInvoiceAndInvoiceItems')->name('get-invoice');
Route::resource('/products', 'ProductController');
Route::resource('/invoices', 'InvoiceController');