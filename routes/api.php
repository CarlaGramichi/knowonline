<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/vtex/list', 'VtexController@sync' ); //Endpoint para obtener el listado de ordenes
Route::get('/vtex/orders', 'VtexController@orders' ); //Endpoint para obtener una orden
Route::get('/vtex/store', 'VtexController@storeOrders' ); //Endpoint para almacenar las ordenes en la DB.

//Route::get('/orders', function (){
//    return 'Ordenes';
//});

//Route::apiResource('orders', 'OrderController');