<?php

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

Route::get('/'          , [DefaultController::class, 'index'])->name('dashboard');
Route::get('/fasyankes' , [DefaultController::class, 'fasyankes'])->name('fasyankes');
Route::get('/billing'   , [DefaultController::class, 'billing'])->name('billing');



