<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisCutiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfilesController;
use App\Http\Controllers\UsersProfileController;

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

// Auth Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/template', [TemplateController::class, 'index'])->name('template');
    Route::resource('templates', TemplateController::class)->except(['show', 'create', 'edit']);

    Route::get('/jeniscuti', [JenisCutiController::class, 'index'])->name('jeniscuti');
    Route::resource('jeniscuti', JenisCutiController::class)->except(['show', 'create', 'edit']);

    Route::get('/UsersProfiles', [UsersProfileController::class, 'index'])->name('UsersProfiles');
    Route::resource('user-profiles', UsersProfileController::class)->except(['show']);

    // ProfileRoute::resource('user-profiles', UserProfileController::class)->except(['show']);
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User Management (Admin Only)
    Route::middleware('can:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
