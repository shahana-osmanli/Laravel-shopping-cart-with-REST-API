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
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::put('/update', 'AuthController@update');
Route::get('/get', 'AuthController@getAuthUser');
Route::get('/products', 'ProductController@getAll');
Route::get('/products/{id}', 'ProductController@getOne');

Route::post('/addtocart/{id}', 'CartController@addToCart');
Route::get('/getcart', 'CartController@getCartProducts');
Route::delete('/deleteproduct/{id}', 'CartController@deleteProduct');
Route::get('/getquantity/{id}', 'CartController@getQuantity');
Route::put('/increase/{id}', 'CartController@increase');
Route::put('/decrease/{id}', 'CartController@decrease');



Route::get('/test', function(){
    return 'Login olunub';
})->middleware('jwt.auth');
