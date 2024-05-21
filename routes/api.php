<?php


use App\Http\Controllers\API\BaseController as B;
use App\Http\Controllers\API\DefaultController as API;
use App\Http\Controllers\API\Outh2Token;
use App\Http\Controllers\DefaultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






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
Route::post('v1/api-endpoint', [API::class, 'gatewayApiRequest'])->name('gateway');

Route::group(['middleware' => ['secure.logging', 'secure.access']], function () {
    Route::post('fasyankes',                        [API::class, 'fasyankes'])->name('store.fasyankes');
    Route::get('v_fasyankes/{const_users}',         [API::class, 'v_fasyankes'])->name('get.fasyankes');
    Route::get('g_fasyankes',                       [API::class, 'g_fasyankes'])->name('all.fasyankes');
    Route::get('d_fasyankes/{const_users}',         [API::class, 'd_fasyankes'])->name('del.fasyankes');
    Route::get('gb/select',                         [API::class, 'gb_fasyankes'])->name('gb.fasyankes');
    Route::post('c_billing',                        [API::class, 'c_billing'])->name('bill.payment');
    Route::get('g_billing',                         [API::class, 'g_billing'])->name('all.payment');
    Route::get('c_payment/{conditional}/{const_users}',[API::class, 'c_payment'])->name('due.payment');
    Route::get('d_payment/{const_users}',           [API::class, 'd_payment'])->name('detail.payment');
    Route::get('u_status/{const_users}/{status}',   [API::class, 'u_status'])->name('set.license');
    Route::post('/log-out'                         , [API::class, 'v_destroy'])->name('logout');
    Route::get('/log-out/{id}'                         , [API::class, 'a_destroy'])->name('logout.auto');
});

Route::post('create_admin_account',             [API::class, 'create_admin']);
Route::group(['middleware' => 'throttle:5,1'], function () {
    Route::post('v_login',                          [API::class, 'v_login'])->name('auth.verified');
    Route::post('v_token',                          [API::class, 'v_token'])->name('auth.token');

});
Route::get('ip',   [API::class, 'verificationIP'])->name('ip');


