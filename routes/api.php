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

// Authentication
Route::post('/registration', 'API\JwtAuthController@registration');
Route::post('/login', 'API\JwtAuthController@login');
Route::post('/logout', 'API\JwtAuthController@logout');
Route::get('/user-profile', 'API\JwtAuthController@userProfile');

// Simple CRUD Api
Route::post('/create', 'API\ProductsController@create');
Route::get('/all-products', 'API\ProductsController@all_products');
Route::get('/read/{slug}', 'API\ProductsController@read');
Route::post('/update/{slug}', 'API\ProductsController@update');
Route::get('/delete/{slug}', 'API\ProductsController@delete');
