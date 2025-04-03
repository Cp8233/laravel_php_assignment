<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Show all product api route
Route::get('products', [ProductController::class, 'index']);
//add product api route
Route::post('add_products', [ProductController::class, 'store']);
//Show a product detail api route with id
Route::post('product_detail', [ProductController::class, 'product_detail']);
//update product api route
Route::post('product_update', [ProductController::class, 'product_update']);
//delete product api route
Route::post('product_delete', [ProductController::class, 'product_delete']);
