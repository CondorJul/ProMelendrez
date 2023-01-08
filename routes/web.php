<?php

use App\Http\Controllers\API\v1\PaymentController;
use App\Http\Controllers\API\v1\ReportsController;
use Illuminate\Support\Facades\Route;

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
    //return redirect()->intended('http:');

 //   return view('welcome');
});

Route::get('/v1/payments/{payToken}/proof-of-payment', [PaymentController::class, 'proofOfPayment'])->middleware(['cors']);

Route::get('/v1/reports/{prdsId}/exercise-monitoring/{bussId}', [ReportsController::class, 'reportControlMonitoring'])->middleware(['cors']);
Route::get('/v1/reports/all-periods/{bussId}', [ReportsController::class, 'reportAllPeriods'])->middleware(['cors']);

Route::get('/mail/test', function () {
    //return redirect()->intended('http:');

    return view('mails.test');
});