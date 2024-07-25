<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
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

Route::get('/', [AuthController::class,'index'])->name('login');
Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('do-register', [AuthController::class, 'doRegister'])->name('do-register');;
Route::post('do-login',[AuthController::class,'checkLogin'])->name('do-login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('urls', [UrlController::class, 'index'])->name('urls.index');
    Route::post('urls', [UrlController::class, 'store'])->name('urls.store');
    Route::delete('urls/{id}', [UrlController::class, 'destroy'])->name('urls.destroy');
    Route::put('urls/{url}', [UrlController::class, 'update'])->name('urls.update');
    Route::patch('urls/deactivate/{id}', [UrlController::class, 'deactivate'])->name('urls.deactivate');
    Route::get('plan-upgrade', [AuthController::class, 'showPlanUpgradeForm'])->name('plan.upgrade');
    Route::post('plan-upgrade', [AuthController::class, 'upgradePlan']);
});

Route::get('/{short_url}', [UrlController::class, 'redirect'])->name('url.redirect');