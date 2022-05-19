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

/***************************************/
/*           Users endpoints           */
/***************************************/
Route::post("register", "App\Http\Controllers\API\UserController@store")
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        "username" => "required|max:100|unique:users|alpha_num",
        "email" => "required|email|unique:users",
        "password" => "required|min:8"
    ]));

Route::post("login", "App\Http\Controllers\API\UserController@login")
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        "username" => "required|alpha_num",
        "password" => "required"
    ]));

Route::patch("users", "App\Http\Controllers\API\UserController@patch")
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        "username" => "unique:users|max:100|alpha_num",
        "email" => "unique:users|email"
    ]));

Route::get("users", "App\Http\Controllers\API\UserController@get")
    ->middleware(EnsureTokenIsValid::class);


/***************************************/
/*         Locations endpoints         */
/***************************************/
Route::get("locations", "App\Http\Controllers\API\LocationController@get")
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
        "id" => "nullable|size:5|exists:locations"
    ]));


Route::post("locations", "App\Http\Controllers\API\LocationController@store")
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
            "name" => "required|max:100|min:3|alpha_num",
            "lat" => "required|numeric",
            "long" => "required|numeric",
            "folder_id" => "nullable|size:5|exists:folders.id" //Should be a comma BUT because we CAN'T have one there, I just use a period and replaced it in the middleware by a comma
    ]));

Route::delete("locations", "App\Http\Controllers\API\LocationController@delete")
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
            "id" => "required|size:5|exists:locations"
        ]));


/***************************************/
/*          Folders endpoints          */
/***************************************/
Route::get("folders", "App\Http\Controllers\API\FolderController@get")
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
            "folder_id" => "nullable|size:5|exists:folders.id" //Should be a comma BUT because we CAN'T have one there, I just use a period and replaced it in the middleware by a comma
        ]));

Route::post("folders", "App\Http\Controllers\API\FolderController@store")
    ->middleware(EnsureTokenIsValid::class)
    ->middleware("\App\Http\Middleware\EnsureAllRequiredParams:".serialize([
            "name" => "required|max:100|min:3|alpha_num",
            "parent_id" => "nullable|size:5|exists:folders.id" //Should be a comma BUT because we CAN'T have one there, I just use a period and replaced it in the middleware by a comma
        ]));
