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

// Public Routes (Accessible by Staff/Guest)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/suggestions', [DashboardController::class, 'suggestions'])->name('dashboard.suggestions');
Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Index-only public routes for resources
Route::get('surat-masuk', [\App\Http\Controllers\SuratMasukController::class, 'index'])->name('surat-masuk.index');
Route::get('surat-keluar', [\App\Http\Controllers\SuratKeluarController::class, 'index'])->name('surat-keluar.index');
Route::get('arsip-pembibitan', [\App\Http\Controllers\ArsipPembibitanController::class, 'index'])->name('arsip-pembibitan.index');
Route::get('arsip-hijauan', [\App\Http\Controllers\ArsipHijauanController::class, 'index'])->name('arsip-hijauan.index');
Route::get('dokumen', [\App\Http\Controllers\DokumenController::class, 'index'])->name('dokumen.index');
Route::get('dokumen/{dokumen}/download', [\App\Http\Controllers\DokumenController::class, 'download'])->name('dokumen.download');
Route::get('dokumen/{dokumen}/preview', [\App\Http\Controllers\DokumenController::class, 'preview'])->name('dokumen.preview');

// Admin-only Write Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('surat-masuk', \App\Http\Controllers\SuratMasukController::class)->except(['index']);
    Route::post('surat-keluar/{surat_keluar}/send', [\App\Http\Controllers\SuratKeluarController::class, 'send'])->name('surat-keluar.send');
    Route::resource('surat-keluar', \App\Http\Controllers\SuratKeluarController::class)->except(['index']);
    Route::resource('arsip-pembibitan', \App\Http\Controllers\ArsipPembibitanController::class)->except(['index']);
    Route::resource('arsip-hijauan', \App\Http\Controllers\ArsipHijauanController::class)->except(['index']);
    Route::resource('dokumen', \App\Http\Controllers\DokumenController::class)->except(['index'])->parameters([
        'dokumen' => 'dokumen'
    ]);
    
    Route::resource('kategori-dokumen', \App\Http\Controllers\KategoriDokumenController::class)->except(['create', 'edit', 'show']);
    Route::post('dokumen/kategori', [\App\Http\Controllers\DokumenController::class, 'storeKategori'])->name('dokumen.kategori.store');
    
    // User Management
    Route::post('users/{user}/approve', [\App\Http\Controllers\UserManagementController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/approve', [\App\Http\Controllers\UserManagementController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [\App\Http\Controllers\UserManagementController::class, 'reject'])->name('users.reject');
    Route::resource('users', \App\Http\Controllers\UserManagementController::class);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
// Route khusus untuk memperbaiki link storage di hosting (Byethost/Shared Hosting)
Route::get('/fix-storage', function () {
    try {
        // 1. Lokasi folder
        $target = storage_path('app/public');
        $link = public_path('storage');
        $docFolder = $link . '/documents';
        
        // 2. Set chmod 777 ke folder-folder utama
        if (file_exists($link)) {
            chmod($link, 0777); 
        }
        if (file_exists($docFolder)) {
            chmod($docFolder, 0777);
        }

        // 3. Coba buat symbolic link (jika belum ada)
        if (file_exists($link) && !is_link($link)) {
            // rename($link, $link . '_backup_' . time());
        }
        
        if (!file_exists($link)) {
            app('files')->link($target, $link);
        }
        
        return "✅ chmod 777 berhasil diterapkan dan link storage telah diperiksa. Silakan coba akses dokumen Anda lagi.";
    } catch (\Exception $e) {
        return "❌ Gagal: " . $e->getMessage();
    }
});

Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.update');
