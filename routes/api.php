<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\v1\AppointmentController;
use App\Http\Controllers\API\v1\AppointmentTempController;
use App\Http\Controllers\API\v1\BusinessController;
use App\Http\Controllers\API\v1\CardsController;
use App\Http\Controllers\API\v1\CategoryController;
use App\Http\Controllers\API\v1\CopyToClipboardController;
use App\Http\Controllers\API\v1\DashboardController;
use App\Http\Controllers\API\v1\PersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\TellerController;
use App\Http\Controllers\API\v1\PruebaController;
use App\Http\Controllers\API\v1\RoleController;
use App\Http\Controllers\API\v1\PermissionController;
use App\Models\Teller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\v1\VideosController;
use App\Http\Controllers\API\v1\HeadquarterController;
use App\Http\Controllers\API\v1\PeriodController;
use App\Http\Controllers\API\v1\DBusinessPeriodController;
use App\Http\Controllers\API\v1\MailController;
use App\Http\Controllers\API\v1\PaymentController;
use App\Http\Controllers\API\v1\PaymentMethodController;
use App\Http\Controllers\API\v1\PeriodPaymentController;
use App\Http\Controllers\API\v1\ReportsController;
use App\Http\Controllers\API\v1\ServicesController;
use App\Http\Controllers\API\v1\ServiceProvidedController;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Routing\RouteRegistrar;

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

Route::get('/storage-link-public', function () {
    $target = storage_path('app/public');
    $link = public_path('/storage');
    symlink($target, $link);
    echo "symbolic link created successfully";
});
Route::get('/storage-link-profile-images', function () {
    $target = storage_path('app/private/profile-images');
    $link = public_path('/strg/api/s/profile-images');
    symlink($target, $link);
    echo "symbolic link created successfully";
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('test', [TestController::class, 'index'])->middleware(['auth:sanctum', 'can:/permissions']);


/*Categoria */
Route::get('v1/categories/all-by-hq', [CategoryController::class, 'allByHQ']);
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
Route::put('v1/auth/change-password-with-auth', [AuthController::class, 'changePasswordWithAuth'])->middleware(['auth:sanctum']);;
Route::post('v1/auth/upload-profile-image-with-auth', [AuthController::class, 'uploadProfileImageWithAuth'])->middleware(['auth:sanctum']);;
Route::put('v1/auth/upd-user-with-person-with-auth', [AuthController::class, 'updUserWithPersonWithAuth'])->middleware(['auth:sanctum']);


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
Route::get('/v1/tellers/get-join-person-by-hq', [TellerController::class, 'getJoinPersonByHQ']);

Route::get('v1/tellers/all-by-hq', [TellerController::class, 'allByHQ']);
Route::get('v1/tellers/{id}', [TellerController::class, 'find']);
Route::get('v1/tellers', [TellerController::class, 'index']);
Route::post('v1/tellers', [TellerController::class, 'store']);
Route::get('v1/tellers', [TellerController::class, 'index']);

Route::put('v1/tellers', [TellerController::class, 'update']);
Route::delete('v1/tellers/{id}', [TellerController::class, 'destroy']);
Route::get('/v1/tellers/search-by-code/{code}', [TellerController::class, 'searchByCode']);
Route::put('/v1/tellers/{id}/upd-user', [TellerController::class, 'updUser']);
Route::delete('/v1/tellers/{id}/remove-user', [TellerController::class, 'removeUser']);

Route::put('/v1/tellers/{id}/upd-state', [TellerController::class, 'updState']);


/*pivot categories */
Route::get('/v1/tellers/{id}/get-categories', [TellerController::class, 'getCategories']);
Route::put('/v1/tellers/{id}/attach-category', [TellerController::class, 'attachCategory']);
Route::delete('/v1/tellers/{id}/detach-category/{catId}', [TellerController::class, 'detachCategory']);
/** */

/*appointment_temp */
Route::delete('v1/appointment-temps/migrate-tickets', [AppointmentTempController::class, 'migrateTickets']);


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




Route::put('v1/appointment-temps/{ids}/teller', [AppointmentTempController::class, 'updateTeller']);




/* Bussines*/
Route::put('v1/business/{ids}/updStateBuss', [BusinessController::class, 'updateBusinessState']);
Route::put('v1/business/{ids}/updTeller', [BusinessController::class, 'updateBusinessTeller']);
Route::get('v1/business/getBusinessJoinTeller', [BusinessController::class, 'getBusinessOfTeller']);
Route::get('v1/business/getCantTellerUsers', [BusinessController::class, 'getTellerJoinUsers']);
Route::get('v1/bussines', [BusinessController::class, 'index']);
Route::get('v1/bussines/all-summarized', [BusinessController::class, 'allSummarized']);
Route::post('v1/business/exist-ruc', [BusinessController::class, 'existRuc']);
Route::post('v1/business/exist-fileNumber', [BusinessController::class, 'existFileNumber']);
Route::post('v1/business/add-business-with-person', [BusinessController::class, 'addBusinessWithPerson']);
Route::get('/v1/business/{bussId}', [BusinessController::class, 'viewBusinessPerson']);
Route::put('v1/business/upd-bussData', [BusinessController::class, 'updateBusinessData']);
Route::put('v1/business/upd-perData', [BusinessController::class, 'updatePersonData']);
Route::put('v1/business/upd-afiData', [BusinessController::class, 'updateAfiliationData']);
Route::put('v1/business/upd-adiData', [BusinessController::class, 'updateAditionalData']);



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


Route::group(['middleware' => ['auth:sactum']], function () {
});


Route::get('v1/roles', [RoleController::class, 'index']);
Route::get('v1/roles/{roleName}/permissions', [RoleController::class, 'getPermissions']);
Route::put('v1/roles/{roleName}/sync-permissions', [RoleController::class, 'syncPermissions']);


Route::post('v1/roles', [RoleController::class, 'store']);
Route::put('v1/roles/{id}', [RoleController::class, 'update']);
Route::delete('v1/roles/{id}', [RoleController::class, 'delete']);

Route::get('v1/permissions', [PermissionController::class, 'index']);

/*Headquarter */
Route::get('/v1/headquarters', [HeadquarterController::class, 'index']);
Route::get('/v1/headquarters/search-by-name/{name}', [HeadquarterController::class, 'searchByName']);

Route::post('/v1/headquarters', [HeadquarterController::class, 'store']);
Route::put('/v1/headquarters/{id}', [HeadquarterController::class, 'update']);
Route::delete('/v1/headquarters/{id}', [HeadquarterController::class, 'destroy']);

/*Dashboard */
Route::get('/v1/dashboard/counter-cards', [DashboardController::class, 'getCounterCards']);

/*Appointment */
Route::get('/v1/appointments/get-all-by', [AppointmentController::class, 'getAllBy']);
Route::get('/v1/appointments/getTellers', [AppointmentController::class, 'getTellers']);
Route::get('/v1/appointments/getCategories', [AppointmentController::class, 'getCategories']);



/*Modificaciones 22/05/2022 */

/*Business with periods*/
Route::get('/v1/business/{bussId}/periods', [BusinessController::class, 'allPeriods']);
Route::post('/v1/business/{bussId}/periods', [BusinessController::class, 'updPeriod']);
//Route::put('/v1/business/{bussId}/periods/{prdsId}', [DBusinessPeriodController::class, 'update']);
Route::delete('/v1/business/{bussId}/periods/{prdsId}', [BusinessController::class, 'delPeriod']);


/*Periodos */
Route::get('/v1/periods', [PeriodController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/v1/periods', [PeriodController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/v1/periods/{id}', [PeriodController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/v1/periods/{id}', [PeriodController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::put('/v1/periods/{prdsId}/change-state', [PeriodController::class, 'changeState'])->middleware(['auth:sanctum']);

/*PaymentMethods */
Route::get('/v1/payment-methods', [PaymentMethodController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/v1/payment-methods', [PaymentMethodController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/v1/payment-methods/{id}', [PaymentMethodController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/v1/payment-methods/{id}', [PaymentMethodController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::put('/v1/payment-methods/{id}/change-state', [PaymentMethodController::class, 'changeState'])->middleware(['auth:sanctum']);



/*Detalle negocio y periodo */
Route::get('/v1/d-business-periods', [DBusinessPeriodController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/v1/d-business-periods/addDBP', [DBusinessPeriodController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/v1/d-business-periods/{id}', [DBusinessPeriodController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/v1/d-business-periods/{id}', [DBusinessPeriodController::class, 'destroy'])->middleware(['auth:sanctum']);

/*Servicios que se ofrecen */
Route::get('/v1/services-provided/all-by-dbp', [ServiceProvidedController::class, 'allByDBP'])->middleware(['auth:sanctum']);
Route::get('/v1/services-provided{id}', [ServiceProvidedController::class, 'find'])->middleware(['auth:sanctum']);
Route::get('/v1/services-provided', [ServiceProvidedController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/v1/services-provided/addServicesProvided', [ServiceProvidedController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/v1/services-provided/{spId}', [ServiceProvidedController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/v1/services-provided/{id}', [ServiceProvidedController::class, 'destroy'])->middleware(['auth:sanctum']);



/* Gestion de Servicios */
Route::get('v1/services', [ServicesController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('v1/services/addServices', [ServicesController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('v1/services/updServices', [ServicesController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('v1/services/delServices/{svId}', [ServicesController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::delete('v1/services/stateServices/{svId}', [ServicesController::class, 'stateService'])->middleware(['auth:sanctum']);

/*Pagos */
/*Detalle negocio y periodo */
Route::get('/v1/payments', [PaymentController::class, 'all'])->middleware(['auth:sanctum']);
Route::post('/v1/payments', [PaymentController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/v1/payments/{id}', [PaymentController::class, 'update'])->middleware(['auth:sanctum']);
Route::put('/v1/payments/{payId}/cancel', [PaymentController::class, 'cancel'])->middleware(['auth:sanctum']);
Route::put('/v1/payments/{payId}/ticket', [PaymentController::class, 'setTicket'])->middleware(['auth:sanctum']);
Route::put('/v1/payments/{payId}/invoice', [PaymentController::class, 'setInvoice'])->middleware(['auth:sanctum']);
Route::put('/v1/payments/{payId}/receipt-honorary', [PaymentController::class, 'setReceiptHonorary'])->middleware(['auth:sanctum']);



Route::delete('/v1/payments/{id}', [PaymentController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::get('/v1/payments/{payToken}/proof-of-payment', [PaymentController::class, 'proofOfPayment']);
Route::get('/v1/payments/{payToken}/proof-of-payment-json', [PaymentController::class, 'proofOfPaymentJson']);

Route::get('/v1/report/{prdsId}/control-monitoring-json/{bussId}', [ReportsController::class, 'controlMonitoringJson']);


/*periodos de pago */
Route::get('/v1/period-payments', [PeriodPaymentController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/v1/period-payments', [PeriodPaymentController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/v1/period-payments/{id}', [PeriodPaymentController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/v1/period-payments/{id}', [PeriodPaymentController::class, 'destroy'])->middleware(['auth:sanctum']);


/*Copy to clipboard */
Route::post('/v1/copy-to-clipboard', [CopyToClipboardController::class, 'store'])->middleware(['auth:sanctum']);


/*ENviar emails */
Route::get('/v1/send-mail-test/{mail}', [MailController::class, 'sendMail']);
Route::get('/v1/send-mail-test2', function () {
    return view('mails.test', ['name' => 'James']);
});