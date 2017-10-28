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
Route::post('avc','OrderController@info');
/* Route::get('/', function () {
    return view('welcome');
}); */

Route::any('avi','Api\OrderController@addOrder');

Route::any('sendsms','Api\OrderController@sendsms');
Route::any('sendsms2','Api\OrderController@sendsms2');
Route::any('postage/{id}','Api\PostageController@add');
Route::any('index','Admin\Index@index');
Route::any('welcome','Admin\Index@welcome');
Route::any('order','Admin\Index@order');
Route::any('view/{id}','Admin\Index@view');
Route::any('test','Api\AuthController@index');