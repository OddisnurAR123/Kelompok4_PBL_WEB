<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPenggunaController;
use App\Http\Controllers\KategoriKegiatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KegiatanEksternalController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\JabatanKegiatanController;
use App\Http\Controllers\AgendaKegiatanController;
use App\Http\Controllers\DetailKegiatanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;


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

Route::get('/', [WelcomeController::class, 'index'])->name('dashboard');

Route::get('/notifikasi', [NotifikasiController::class, 'getNotifications']);

// Pattern untuk parameter ID harus berupa angka
Route::pattern('id', '[0-9]+');

// Routes untuk autentikasi login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [AuthController::class, 'registerForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register');


    Route::get('/jenis_pengguna', [JenisPenggunaController::class, 'index']);
    Route::post('/jenis_pengguna/list', [JenisPenggunaController::class, 'list']);
    Route::get('/jenis_pengguna/{id}/show', [JenisPenggunaController::class, 'show']);
    Route::get('/jenis_pengguna/create', [JenisPenggunaController::class, 'create']);
    Route::post('/jenis_pengguna/store', [JenisPenggunaController::class, 'store']);
    Route::get('/jenis_pengguna/{id}/edit', [JenisPenggunaController::class, 'edit']);
    Route::post('/jenis_pengguna/{id}/update', [JenisPenggunaController::class, 'update']);
    Route::get('/jenis_pengguna/{id}/delete', [JenisPenggunaController::class, 'delete']);
    Route::get('/pic', [JenisPenggunaController::class, 'getPic']);
    Route::get('/anggota', [JenisPenggunaController::class, 'getAnggota']);    


    Route::get('/kategori_kegiatan', [KategoriKegiatanController::class, 'index'])->name('kategori_kegiatan.index');
    Route::post('/kategori_kegiatan/list', [KategoriKegiatanController::class, 'list']);
    Route::get('kategori_kegiatan/{id}/show', [KategoriKegiatanController::class, 'show'])->name('kategori_kegiatan.show');
    Route::get('/kategori_kegiatan/create', [KategoriKegiatanController::class, 'create']); 
    Route::post('/kategori_kegiatan', [KategoriKegiatanController::class, 'store'])->name('kategori_kegiatan.store');
    Route::get('/kategori_kegiatan/{id}/edit', [KategoriKegiatanController::class, 'edit'])->name('kategori_kegiatan.edit');
    Route::put('/kategori_kegiatan/{id}/update', [KategoriKegiatanController::class, 'update']);
    Route::get('/kategori_kegiatan/{id}/delete', [KategoriKegiatanController::class, 'confirm']);
    Route::delete('/kategori_kegiatan/{id}/delete', [KategoriKegiatanController::class, 'delete']);

    Route::get('/jabatan_kegiatan', [JabatanKegiatanController::class, 'index']);
    Route::post('/jabatan_kegiatan/list', [JabatanKegiatanController::class, 'list']);
    Route::get('/jabatan_kegiatan/{id}/show', [JabatanKegiatanController::class, 'show'])->name('jabatan_kegiatan.show');
    Route::get('/jabatan_kegiatan/create', [JabatanKegiatanController::class, 'create']); 
    Route::post('/jabatan_kegiatan', [JabatanKegiatanController::class, 'store'])->name('jabatan_kegiatan.store');
    Route::post('/jabatan_kegiatan/store', [JabatanKegiatanController::class, 'store']);
    Route::get('/jabatan_kegiatan/{id}/edit', [JabatanKegiatanController::class, 'edit']);
    Route::put('/jabatan_kegiatan/{id}/update', [JabatanKegiatanController::class, 'update']);
    Route::get('/jabatan_kegiatan/{id}/delete', [JabatanKegiatanController::class, 'confirm']);
    Route::delete('/jabatan_kegiatan/{id}/delete', [JabatanKegiatanController::class, 'delete']);
    
    // Kegiatan Routes
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan/list', [KegiatanController::class, 'list']);
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->name('kegiatan.show');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/update/{id_kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::get('/kegiatan/{id}/delete', [KegiatanController::class, 'confirm'])->name('kegiatan.delete');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'delete'])->name('kegiatan.destroy');
    Route::get('/kegiatan/import', [KegiatanController::class, 'import'])->name('kegiatan.import');
    Route::post('/kegiatan/import_ajax', [KegiatanController::class, 'import_ajax'])->name('kegiatan.import.ajax');
    Route::get('/kegiatan/export_excel', [KegiatanController::class, 'export_excel'])->name('kegiatan.export.excel');
    Route::get('/kegiatan/export_pdf', [KegiatanController::class, 'export_pdf'])->name('kegiatan.export.pdf');

    // Detail Kegiatan Routes
    Route::get('/detail_kegiatan', [DetailKegiatanController::class, 'index'])->name('detail_kegiatan.index');
    Route::post('/detail_kegiatan/list', [DetailKegiatanController::class, 'list']);
    Route::get('/detail_kegiatan/create', [DetailKegiatanController::class, 'create'])->name('detail_kegiatan.create');
    Route::post('/detail_kegiatan/store', [DetailKegiatanController::class, 'store'])->name('detail_kegiatan.store');
    Route::get('/detail_kegiatan/{id}', [DetailKegiatanController::class, 'show'])->name('detail_kegiatan.show');
    Route::get('/detail_kegiatan/{id}/edit', [DetailKegiatanController::class, 'edit'])->name('detail_kegiatan.edit');
    Route::put('/detail_kegiatan/update/{id_detail_kegiatan}', [DetailKegiatanController::class, 'update'])->name('detail_kegiatan.update');
    Route::post('/detail_kegiatan/export_excel', [DetailKegiatanController::class, 'export_excel'])->name('detail_kegiatan.export_excel');
    Route::post('/detail_kegiatan/export_pdf', [DetailKegiatanController::class, 'export_pdf'])->name('detail_kegiatan.export_pdf');

    Route::get('/kegiatan_eksternal', [KegiatanEksternalController::class, 'create'])->name('kegiatan_eksternal.create'); 
    Route::post('/kegiatan_eksternal', [KegiatanEksternalController::class, 'store'])->name('kegiatan_eksternal.store'); 

    Route::get('/agenda', [AgendaKegiatanController::class, 'index'])->name('agenda.index');
    Route::post('/agenda/list', [AgendaKegiatanController::class, 'list']);
    Route::get('agenda/{id}/show', [AgendaKegiatanController::class, 'show'])->name('agenda.show');
    Route::get('/agenda/create', [AgendaKegiatanController::class, 'create']);
    Route::post('/agenda/store', [AgendaKegiatanController::class, 'store'])->name('agenda.store');    
    Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{id}/update', [AgendaKegiatanController::class, 'update']);
    Route::get('/agenda/{id}/delete', [AgendaKegiatanController::class, 'confirm']);
    Route::delete('/agenda/{id}/delete', [AgendaKegiatanController::class, 'delete']);

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna/list', [PenggunaController::class, 'list']);
    Route::get('/pengguna/{id}/show', [PenggunaController::class, 'show'])->name('pengguna.show');
    Route::get('/pengguna/create', [PenggunaController::class, 'create']);
    Route::post('/pengguna/store', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{id}/update', [PenggunaController::class, 'update']);
    Route::get('/pengguna/{id}/delete', [PenggunaController::class, 'confirm']);
    Route::delete('/pengguna/{id}/delete', [PenggunaController::class, 'delete']);    
    Route::post('/pengguna/import', [PenggunaController::class, 'import']);
    Route::get('/pengguna/export_pdf', [PenggunaController::class, 'export_pdf']);



