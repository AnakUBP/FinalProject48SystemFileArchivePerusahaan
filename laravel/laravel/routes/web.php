<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisCutiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UsersProfileController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\RiwayatSuratController;
use App\Http\Controllers\LaporanController;

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

Auth::routes();

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.aktivitas');
    Route::get('/log-aktivitas/load', [ActivityLogController::class, 'loadMore'])->name('log.aktivitas.load');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/template', [TemplateController::class, 'index'])->name('template');
    Route::resource('templates', TemplateController::class)->except(['show', 'create', 'edit']);

    Route::get('/jeniscuti', [JenisCutiController::class, 'index'])->name('jeniscuti');
    Route::resource('jeniscuti', JenisCutiController::class)->except(['show', 'create', 'edit']);

    Route::get('/UsersProfiles', [UsersProfileController::class, 'index'])->name('UsersProfiles');
    Route::resource('user-profiles', UsersProfileController::class)->except(['show']);

    Route::get('/manajemen-cuti', [PengajuanCutiController::class, 'index'])->name('manajemen-cuti');

    // 2. Rute kustom untuk proses approval HARUS didefinisikan SEBELUM resource.
    Route::post('/pengajuan-cuti/{pengajuanCuti}/proses', [PengajuanCutiController::class, 'update'])->name('cuti.proses');

    // 3. Rute Resource untuk semua aksi di belakang layar.
    Route::resource('pengajuan-cuti', PengajuanCutiController::class)->except([
        'index', 
        'create', 
        'edit'    
    ]);

    Route::get('/verify/cuti-signature/{pengajuanCuti}', [PengajuanCutiController::class, 'verifySignature'])->name('cuti.signature.verify');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/riwayat', [RiwayatSuratController::class, 'index'])->name('riwayat');
    Route::get('/riwayat-surat/{pengajuanCuti}', [RiwayatSuratController::class, 'show'])->name('riwayat.show');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
});

