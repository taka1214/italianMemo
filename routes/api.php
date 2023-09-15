<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiItemController;
use App\Http\Controllers\PostController;
use Spatie\FlareClient\Api;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// api.php
Route::get('/items/spreadsheet', [ApiItemController::class, 'getSpreadsheetItems']);
Route::get('/items/kentei', [ApiItemController::class, 'getKenteiItems']);
Route::get('/items/shuffle', [ApiItemController::class, 'shuffleItems']);
Route::get('/items/shuffleKentei', [ApiItemController::class, 'shuffleItemsKentei']);

Route::post('/items', [PostController::class, 'store']); 
Route::get('/items/{id}', [ApiItemController::class, 'getItem']);
Route::post('/item/{id}', [ApiItemController::class, 'update']);
Route::delete('/item/{id}', [ApiItemController::class, 'delete']);
