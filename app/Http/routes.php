<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'ProductController@index');

Route::get('/cart/{id}', 'ProductController@getAddToCart');

Route::get('/shoppingCart', 'ProductController@getShoppingCart');

Route::get('/reduce/{id}', 'ProductController@getReduceByOne');

Route::get('/remove/{id}', 'ProductController@getRemoveAll');

Route::group(['middleware' => 'auth'], function (){

    Route::get('/profile', 'UserController@getProfile');

    Route::get('/logout', 'UserController@getLogout');

    Route::get('/checkout', 'ProductController@getCheckout');
    Route::post('/checkout', 'ProductController@postCheckout');
});
Route::group(['middleware' => 'guest'], function (){

    Route::get('/signup', 'UserController@getSignup');
    Route::post('/signup', 'UserController@postSignup');

    Route::get('/signin', 'UserController@getSignin');
    Route::post('/signin', 'UserController@postSignin');
});


