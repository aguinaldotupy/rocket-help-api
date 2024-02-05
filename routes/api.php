<?php

use App\Http\Controllers\Api\TicketController;
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

Route::prefix('auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.auth.logout');
});

Route::prefix('tickets')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name('api.tickets.index');
    Route::post('/', [TicketController::class, 'open'])->name('api.tickets.open');
    Route::get('/{ticket}', [TicketController::class, 'show'])->name('api.tickets.show');
    Route::put('/{ticket}', [TicketController::class, 'close'])->name('api.tickets.close');
});
