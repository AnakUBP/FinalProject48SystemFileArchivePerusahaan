<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CutiController;
use App\Http\Controllers\Api\JenisCutiController;
use App\Http\Controllers\Api\ForgotPasswordController;
 // Controller yang akan kita buat

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
// Endpoint untuk meminta link reset password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

// Endpoint untuk mengirim password baru beserta token
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// Endpoint untuk login karyawan
Route::post('/login', [AuthController::class, 'login']);

// Contoh route yang dilindungi
// Hanya user yang sudah login (mengirim token) yang bisa mengakses ini
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profil-saya', [AuthController::class, 'me']);
    Route::get('/jenis-cuti', [JenisCutiController::class, 'index']);
    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti', [CutiController::class, 'store']);
    Route::get('/cuti/{pengajuanCuti}', [CutiController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
});