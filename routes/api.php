<?php

use App\Http\Controllers\API\AutenticarController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\v1\BusinessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\CategoryController;
use App\Http\Controllers\API\v1\PruebaController;

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

Route::get('test', [TestController::class, 'index']);


Route::get('v1/categories', [CategoryController::class, 'index']);
Route::post('v1/categories', [CategoryController::class, 'store']);



Route::get('v1/business', [BusinessController::class, 'index']);

/*Route::get('v1/prueba', [PruebaController::class, 'index']);
Route::post('v1/prueba', [PruebaController::class, 'store']);
Route::get('v1/prueba/{prueba}', [PruebaController::class, 'show']);
Route::put('v1/prueba/{prueba}', [PruebaController::class, 'update']);
Route::delete('v1/prueba/{prueba}', [PruebaController::class, 'destroy']);*/

Route::apiResource('v1/prueba', PruebaController::class);

Route::post('registro', [AutenticarController::class, 'register']);
