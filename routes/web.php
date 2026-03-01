<?php

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

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('surat-masuk', \App\Http\Controllers\SuratMasukController::class);
    Route::post('surat-keluar/{surat_keluar}/send', [\App\Http\Controllers\SuratKeluarController::class, 'send'])->name('surat-keluar.send');
    Route::resource('surat-keluar', \App\Http\Controllers\SuratKeluarController::class);
    Route::resource('arsip-pembibitan', \App\Http\Controllers\ArsipPembibitanController::class);
    Route::resource('arsip-hijauan', \App\Http\Controllers\ArsipHijauanController::class);
    Route::get('dokumen/{dokumen}/download', [\App\Http\Controllers\DokumenController::class, 'download'])->name('dokumen.download');
    Route::resource('dokumen', \App\Http\Controllers\DokumenController::class);
    
    // User Management
    Route::post('users/{user}/approve', [\App\Http\Controllers\UserManagementController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [\App\Http\Controllers\UserManagementController::class, 'reject'])->name('users.reject');
    Route::resource('users', \App\Http\Controllers\UserManagementController::class);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
