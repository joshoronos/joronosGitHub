<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/viewAllProduct', [App\Http\Controllers\HomeController::class, 'viewAllProduct']);
Route::post('/saveProduct', [App\Http\Controllers\HomeController::class, 'post']);
Route::post('/editProduct', [App\Http\Controllers\HomeController::class, 'edit']);
Route::post('/deleteProduct', [App\Http\Controllers\HomeController::class, 'deleteItem']);
Route::post('/saveProductDetails', [App\Http\Controllers\HomeController::class, 'saveProductDetails']);
Route::get('/getBrandDetails', [App\Http\Controllers\HomeController::class, 'showBrand']);
Route::post('/purchaseOrder', [App\Http\Controllers\HomeController::class, 'savePurchaseOrder']);
Route::post('/approvePurchaseOrder', [App\Http\Controllers\HomeController::class, 'approvePurchaseOrder']);
Route::get('/showPurchaseOrder', [App\Http\Controllers\HomeController::class, 'showPurchaseOrder']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
