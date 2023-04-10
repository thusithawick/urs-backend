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

Route::get('/test', 'App\Http\Controllers\ApiController@test');
Route::post('/update-profile/{userId}', 'App\Http\Controllers\ApiController@updateProfile');
Route::post('/register', 'App\Http\Controllers\ApiController@register');
Route::post('/login', 'App\Http\Controllers\ApiController@login');
Route::post('/view-profile', 'App\Http\Controllers\ApiController@viewProfile');
