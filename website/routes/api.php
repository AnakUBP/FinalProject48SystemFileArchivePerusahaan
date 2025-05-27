<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController; // Tambahan

// Login
Route::post('/login', [AuthController::class, 'login']);

// Forgot password request (kirim email reset)
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

// Reset password (form submit dari frontend)
Route::post('/reset-password', [ResetPasswordController::class, 'reset']); 

// Protected Routes - hanya bisa diakses dengan token
Route::middleware('auth:sanctum')->group(function () {

    // Mendapatkan data user yang sedang login
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Ringkasan dashboard
    Route::get('/dashboard-summary', [DashboardController::class, 'summary']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin-only
    Route::middleware('isadmin')->group(function () {
        // Template surat
        Route::get('/templates', [TemplateController::class, 'index']);
        Route::post('/templates', [TemplateController::class, 'store']);
        Route::get('/templates/{id}/download', [TemplateController::class, 'download']);
        Route::delete('/templates/{id}', [TemplateController::class, 'destroy']);

        // Cuti untuk admin
        Route::get('/cuti', [CutiController::class, 'index']);
        Route::put('/cuti/{id}/approve', [CutiController::class, 'approve']);
        Route::put('/cuti/{id}/reject', [CutiController::class, 'reject']);
    });

    // Karyawan-only
    Route::middleware('iskaryawan')->group(function () {
        // Cuti untuk karyawan
        Route::post('/cuti', [CutiController::class, 'store']);
        Route::get('/cuti/me', [CutiController::class, 'myCuti']);
    });
});
