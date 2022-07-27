<?php

use App\Http\Controllers\Frontend\Dashboard\DashboardArticleController;
use App\Http\Controllers\Frontend\Dashboard\DashboardCategoryController;
use App\Http\Controllers\Frontend\Dashboard\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Home\HomeController;
use App\Http\Controllers\Frontend\Home\HomeArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/articles', [HomeArticleController::class, 'index'])->name('homeArticles');
Route::get('/articles/{article:id}', [HomeArticleController::class, 'show']);

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::resource('/dashboard/articles', DashboardArticleController::class)->middleware('auth');
Route::resource('/dashboard/categories', DashboardCategoryController::class)->middleware('admin');


