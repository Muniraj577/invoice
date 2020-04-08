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
Route::get('/invoice-show/{id}', 'InvoiceController@invoicePage')->name('invoicePage');
Route::post('cancel-invoice', 'InvoiceController@cancelInvoice')->name('cancelInvoice');
Route::post('/delete-invoice/{id}', 'InvoiceController@deleteInvoice')->name('deleteInvoice');
Route::get('/invoice/{id}/payment', 'PaymentController@payment')->name('invoices.payment');
Route::post('/invoice-payment/{id}', 'PaymentController@savePayment')->name('invoicePayment.store');
Route::post('/edit-invoice-payment/{id}', 'PaymentController@updatePayment')->name('invoicePayment.update');
Route::get('/download-invoice-pdf/{id}', 'InvoiceController@invoicePdfDownload')->name('invoice.downloadPDF');
Route::get('/payment-pdf-download/{id}', 'PaymentController@paymentPDf')->name('payment-pdf-download');
Route::resource('/products', 'ProductController');
Route::resource('/invoices', 'InvoiceController');
Route::resource('/payments', 'PaymentController');