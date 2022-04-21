<?php

use App\Http\Controllers\API\UserController;
use \App\Http\Middleware\EnsureAllRequiredParams;
use App\Http\Middleware\EnsureTokenIsValid;
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

Route::post("register", 'App\Http\Controllers\API\UserController@store')
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        'username' => 'required|max:100|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8'
    ]));

Route::post("login", 'App\Http\Controllers\API\UserController@login')
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        'username' => 'required',
        'password' => 'required'
    ]));

Route::patch("users", 'App\Http\Controllers\API\UserController@patch')
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        'username' => 'unique:users|max:100',
        'email' => 'unique:users|email'
    ]));


Route::get("locations", 'App\Http\Controllers\API\LocationController@store')->middleware(EnsureTokenIsValid::class);
