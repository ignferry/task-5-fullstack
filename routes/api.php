<?php

use App\Http\Controllers\API\V1\PassportAuthController;
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

Route::post('/register', [PassportAuthController::class, 'register']);
Route::post('/login', [PassportAuthController::class, 'login']);
Route::resource('articles', 'ArticleController')->only(['index', 'show']);
Route::resource('categories', 'CategoryController')->only(['index', 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [PassportAuthController::class, 'logout']);
    Route::resource('articles', 'ArticleController')->only(['store', 'update', 'destroy']);
    Route::resource('categories', 'CategoryController')->only(['store', 'update', 'destroy'])->middleware('api.admin');
});

