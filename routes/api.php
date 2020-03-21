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
Route::put('/update', 'AuthController@update')->middleware('jwt.auth');
Route::get('/get', 'AuthController@getAuthUser')->middleware('jwt.auth');

//Route::post('/register', 'VendorController@registerVendor');


Route::get('/products', 'ProductController@getAll');
Route::get('/products/{id}', 'ProductController@getOne');
Route::post('/addproduct', 'ProductController@addProduct')->middleware(['jwt.auth', 'is_vendor']);

Route::post('/addtocart/{id}', 'CartController@addToCart')->middleware('jwt.auth');//yazsan kifayetdi eger login olmayibsa ozu401 qaytaraca usere
Route::get('/getcart', 'CartController@getCartProducts');
Route::delete('/deletefromcart/{id}', 'CartController@deleteProduct');//Aydin oldu ? hee tam ) Ela onda IsUser-i ozun yazarsan gelmisken o biri bug-a da bax da:) baxaq hansi idi? addtocart postmandae run et
Route::get('/getquantity/{id}', 'CartController@getQuantity'); //bax db-ni gosterirem birinci
Route::put('/increase/{id}', 'CartController@increase');//indi daxil oldugum userin id-si 8-di
Route::put('/decrease/{id}', 'CartController@decrease');

Route::post('/addwish/{id}', 'WishlistController@addWish')->middleware('jwt.auth');
Route::post('/deletewish/{id}', 'WishlistController@deleteWish')->middleware('jwt.auth');


Route::get('/test', function(){
    return 'Login olunub';
})->middleware('jwt.auth');


Route::get('/vendor/test', function(){
    return 'Vendor Page';
})->middleware(['jwt.auth', 'is_vendor']);//burda jwt.auth userin login olub olmadigini yoxlayir, gel kodlarionda bir bug gosterim))

//endbashadda routes den gedey, yazdigin her middleware-ni burda tetbiq edirsen