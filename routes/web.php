<?php

use App\Http\Controllers\API\GatewayClientController;
use App\Http\Controllers\DefaultController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(['middleware' => ['secure.logging', 'secure.access']], function () {
    Route::get('/'          , [DefaultController::class, 'index'])->name('dashboard');
    Route::get('/fasyankes' , [DefaultController::class, 'fasyankes'])->name('fasyankes');
    Route::get('/billing'   , [DefaultController::class, 'billing'])->name('billing');
    Route::get('/payment/history/{const_users}'   , [DefaultController::class, 'payment'])->name('payment.history');

});

Route::get('try/token', [GatewayClientController::class, 'test']);

Route::get('/login', function () {
    $data = [
        'title'     => 'Login RME Panel',
    ];
    return view('login')->with($data);
})->name('login_page');
Route::get('/testing' , [DefaultController::class, 'test'])->name('dwadad');




