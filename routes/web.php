<?php

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

Route::get('/', function () {
    return view('templates.sales');
});
Route::get('/item', 'ItemController@index');
Route::get('/receipt', 'ReceiptController@index');
Route::get('itemset','ItemController@create_set');
