<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\v1\AppointmentTempController;
use App\Http\Controllers\API\v1\BusinessController;
use App\Http\Controllers\API\v1\CardsController;
use App\Http\Controllers\API\v1\CategoryController;
use App\Http\Controllers\API\v1\PersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\TellerController;
use App\Http\Controllers\API\v1\PruebaController;
use App\Models\Teller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\v1\VideosController;

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

/* */

/*Users */
Route::get('v1/users', [AuthController::class, 'index']);
Route::post('v1/users/add-user-with-person', [AuthController::class, 'addUserWithPerson']);
Route::post('v1/users/exist-email', [AuthController::class, 'existEmail']);
Route::put('v1/users/{id}/change-password', [AuthController::class, 'changePassword']);
Route::put('v1/users/upd-user-with-person', [AuthController::class, 'updUserWithPerson']);
Route::get('v1/users/{id}', [AuthController::class, 'find']);

/* Ventanilla*/
Route::get('/v1/tellers/get-join-person', [TellerController::class, 'getJoinPerson']);

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

Route::get('v1/appointment-temps/get-nro-total', [AppointmentTempController::class, 'getNroTotal']);
Route::get('v1/appointment-temps/get-all-by', [AppointmentTempController::class, 'getAllBy']);
Route::post('v1/appointment-temps', [AppointmentTempController::class, 'store']);
Route::get('v1/appointment-temps', [AppointmentTempController::class, 'index']);
Route::get('v1/appointment-temps/get-attention-pending-by-teller/{tellId}', [AppointmentTempController::class, 'getAttentionPendingByTeller']);
Route::get('v1/appointment-temps/get-attention-no-pending', [AppointmentTempController::class, 'getAttentionNoPending']);
Route::get('v1/appointment-temps/get-all', [AppointmentTempController::class, 'getAll']);



Route::put('v1/appointment-temps/start-call-by-teller', [AppointmentTempController::class, 'startCallByTeller']);
Route::put('v1/appointment-temps/{apptmId}/undo-call', [AppointmentTempController::class, 'undoCall']);
Route::put('v1/appointment-temps/{apptmId}/call-again', [AppointmentTempController::class, 'callAgain']);
Route::put('v1/appointment-temps/{apptmId}/finalize-call', [AppointmentTempController::class, 'finalizeCall']);
Route::put('v1/appointment-temps/{apptmId}/transfer-call-to-teller', [AppointmentTempController::class, 'transferCallToTeller']);




Route::put('v1/appointment-temps/{ids}/teller/', [AppointmentTempController::class, 'updateTeller']);



/* Bussines*/
Route::get('v1/bussines', [BusinessController::class, 'index']);
Route::post('v1/business/exist-ruc', [BusinessController::class, 'existRuc']);
Route::post('v1/business/exist-fileNumber', [BusinessController::class, 'existFileNumber']);
Route::post('v1/business/add-business-with-person', [BusinessController::class, 'addBusinessWithPerson']);

/* Person*/
Route::post('v1/person/exist-dni', [PersonController::class, 'existDni']);

/* Videos */
Route::get('v1/videos', [VideosController::class, 'index']);
Route::post('v1/videos/add-videos', [VideosController::class, 'store']);
Route::put('v1/videos/upd-videos', [VideosController::class, 'update']);
Route::delete('v1/videos/{vidId}', [VideosController::class, 'destroy']);
Route::delete('v1/videos/stateVideo/{vidId}', [VideosController::class, 'stateVideo']);

/* Cards */
Route::get('v1/cards', [CardsController::class, 'index']);
Route::post('v1/cards/add-cards', [CardsController::class, 'store']);
Route::put('v1/cards/upd_cards', [CardsController::class, 'update']);
Route::delete('v1/cards/{cardId}', [CardsController::class, 'destroy']);
Route::delete('v1/cards/stateCards/{cardId}', [CardsController::class, 'stateCards']);
