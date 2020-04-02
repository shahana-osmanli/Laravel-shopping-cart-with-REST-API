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
Route::post('/watermark', 'FileController@Watermark');


Route::get('/docx', 'DocumentController@fileDoc');

Route::get('/email', 'MailController@sendMail');

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::put('/update', 'AuthController@update')->middleware('jwt.auth');
Route::get('/get', 'AuthController@getAuthUser')->middleware('jwt.auth');

Route::post('/vendor/register', 'AuthController@register');
Route::post('/vendor/login', 'AuthController@login');
Route::put('/vendor/update', 'AuthController@update');

Route::get('/searchfor', 'ProductController@Search');
Route::get('/products', 'ProductController@getAll');
Route::get('/products/{id}', 'ProductController@getOne');
Route::post('/send/products', 'CheckoutController@Send')->middleware('jwt.auth');


Route::post('/product/add', 'ProductController@addProduct')->middleware(['jwt.auth', 'is_vendor']);
Route::put('/product/{id}/update', 'ProductController@updateProduct')->middleware(['jwt.auth', 'is_vendor']);
Route::delete('/vendor/product/{id}', 'ProductController@deleteProduct')->middleware(['jwt.auth', 'is_vendor']);
Route::get('/vendor/products', 'VendorController@Show')->middleware(['jwt.auth', 'is_vendor']);

Route::post('/upload/file/{id}', 'FileController@fileUpload')->middleware(['jwt.auth', 'is_vendor']);

Route::post('/addtocart/{id}', 'CartController@addToCart')->middleware('jwt.auth');//eger login olmayibsa ozu401 qaytaraca usere
Route::get('/getcart', 'CartController@getCartProducts');
Route::delete('/deletefromcart/{id}', 'CartController@deleteProduct');
Route::get('/getquantity/{id}', 'CartController@getQuantity');
Route::put('/increase/{id}', 'CartController@increase');
Route::put('/decrease/{id}', 'CartController@decrease');

Route::post('/addwish/{id}', 'WishlistController@addWish')->middleware('jwt.auth');
Route::post('/deletewish/{id}', 'WishlistController@deleteWish')->middleware('jwt.auth');


Route::get('/test', function(){
    return 'Login olunub';
})->middleware('jwt.auth');


Route::get('/vendor/test', function(){
    return 'Vendor Page';
})->middleware(['jwt.auth', 'is_vendor']);//burda jwt.auth userin login olub olmadigini yoxlayir, gel kodlarionda bir bug gosterim))
