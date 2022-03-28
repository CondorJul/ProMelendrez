<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\v1\AppointmentTempController;
use App\Http\Controllers\API\v1\BusinessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\TellerController;
use App\Http\Controllers\API\v1\PruebaController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\v1\CategoryController;
use App\Models\AppointmentTemp;
use Monolog\Handler\RotatingFileHandler;

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

/*Categoria */
Route::get('v1/categories/{id}', [CategoryController::class, 'find']);
Route::get('v1/categories', [CategoryController::class, 'index']);
Route::post('v1/categories', [CategoryController::class, 'store']);
Route::put('v1/categories', [CategoryController::class, 'update']);
Route::delete('v1/categories/{id}', [CategoryController::class, 'destroy']);
Route::get('/v1/categories/search-by-code/{code}', [CategoryController::class, 'searchByCode']);



Route::get('v1/business', [BusinessController::class, 'index']);

/*Route::get('v1/prueba', [PruebaController::class, 'index']);
Route::post('v1/prueba', [PruebaController::class, 'store']);
Route::get('v1/prueba/{prueba}', [PruebaController::class, 'show']);
Route::put('v1/prueba/{prueba}', [PruebaController::class, 'update']);
Route::delete('v1/prueba/{prueba}', [PruebaController::class, 'destroy']);*/

Route::apiResource('v1/prueba', PruebaController::class);

Route::post('registro', [AuthController::class, 'register']);


/*Autenticación*/
Route::post('v1/auth/signin', [AuthController::class, 'signIn']);
//Route::post('v1/auth/signin', [AutenticarController::class, 'sign']);


/*Users */
Route::get('v1/users',[AuthController::class, 'index']);
Route::post('v1/users/add-user-with-person', [AuthController::class, 'addUserWithPerson']);
Route::post('v1/users/exist-email', [AuthController::class, 'existEmail']);
Route::put('v1/users/upd-user-with-person', [AuthController::class, 'updUserWithPerson']);
Route::get('v1/users/{id}', [AuthController::class, 'find']);

/* Ventanilla*/
Route::get('v1/tellers/{id}', [TellerController::class, 'find']);
Route::get('v1/tellers', [TellerController::class, 'index']);
Route::post('v1/tellers', [TellerController::class, 'store']);
Route::put('v1/tellers', [TellerController::class, 'update']);
Route::delete('v1/tellers/{id}', [TellerController::class, 'destroy']);
Route::get('/v1/tellers/search-by-code/{code}', [TellerController::class, 'searchByCode']);
Route::put('/v1/tellers/{id}/upd-user', [TellerController::class, 'updUser']);
Route::put('/v1/tellers/{id}/upd-state', [TellerController::class, 'updState']);

/*pivot categories */
Route::get('/v1/tellers/{id}/get-categories', [TellerController::class, 'getCategories']);
Route::put('/v1/tellers/{id}/attach-category', [TellerController::class, 'attachCategory']);
Route::delete('/v1/tellers/{id}/detach-category/{catId}', [TellerController::class, 'detachCategory']);
/** */

/*appointment_temp */
Route::post('v1/appointment-temps', [AppointmentTempController::class, 'store']);

