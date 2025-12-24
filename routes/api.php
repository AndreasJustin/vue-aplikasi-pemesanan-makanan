<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth/login',[AuthController::class,'login']);

Route::post('/create-order', function() {
    return 'create order';
})->middleware(['auth:sanctum', 'ableCreateOrder']);

Route::post('/cook', function() {
    return 'cooking order';
})->middleware(['auth:sanctum', 'ableCook']);
