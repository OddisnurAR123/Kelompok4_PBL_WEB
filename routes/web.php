<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPenggunaController;
use App\Models\JenisPenggunaModel;
use App\Http\Controllers\JenisKegiatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KegiatanEksternalController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\JabatanKegiatanController;
use App\Http\Controllers\AgendaKegiatanController;
use App\Http\Controllers\DetailKegiatanController;


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

// Daftar route agenda
    Route::get('/agenda', [AgendaKegiatanController::class, 'index']);

// Route::middleware(['authorize:ADM,MNG'])->group(function () {        
        Route::get('/jenis_pengguna', [JenisPenggunaController::class, 'index']);
        Route::post('/jenis_pengguna/list', [JenisPenggunaController::class, 'list']);
        Route::get('/jenis_pengguna/create', [JenisPenggunaController::class, 'create']);
        Route::post('/jenis_pengguna', [JenisPenggunaController::class, 'store']);
        Route::post('/jenis_pengguna/store', [JenisPenggunaController::class, 'store']);
        Route::get('/jenis_pengguna/{id}/show', [JenisPenggunaController::class, 'show'])->name('jenis_pengguna.show');
        Route::get('/pic', [JenisPenggunaController::class, 'getPic']);
        Route::get('/anggota', [JenisPenggunaController::class, 'getAnggota']);
        // Route::get('/user/{id}/edit', [UserController::class, 'edit']);
        // Route::put('/user/{id}', [UserController::class, 'update']);
        // Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
        // Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']);
        // Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
        // Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
        // Route::delete('/user/{id}', [UserController::class, 'destroy']);
        // Route::get('/user/import', [UserController::class, 'import']);
        // Route::post('/user/import_ajax', [UserController::class, 'import_ajax']);
        // Route::get('/user/export_excel', [UserController::class, 'export_excel']);
        // Route::get('/user/export_pdf', [UserController::class, 'export_pdf']);
// });

    Route::get('/jenis_kegiatan', [JenisKegiatanController::class, 'index'])->name('jenis_kegiatan.index');
    Route::post('/jenis_kegiatan/list', [JenisKegiatanController::class, 'list']);
    Route::get('jenis_kegiatan/{id}/show', [JenisKegiatanController::class, 'show'])->name('jenis_kegiatan.show');
    Route::get('/jenis_kegiatan/create', [JenisKegiatanController::class, 'create'])->name('jenis_kegiatan.create');
    Route::get('/jenis_kegiatan/{id_kategori_kegiatan}/edit', [JenisKegiatanController::class, 'edit'])->name('jenis_kegiatan.edit');
    Route::delete('/jenis_kegiatan/{id_kategori_kegiatan}', [JenisKegiatanController::class, 'delete'])->name('jenis_kegiatan.delete');

    Route::get('/jabatan_kegiatan', [JabatanKegiatanController::class, 'index']);
    Route::post('/jabatan_kegiatan/list', [JabatanKegiatanController::class, 'list']);
    Route::get('/jabatan_kegiatan/create', [JabatanKegiatanController::class, 'create']);
    Route::post('/jabatan_kegiatan', [JabatanKegiatanController::class, 'store']);
    Route::post('/jabatan_kegiatan/store', [JabatanKegiatanController::class, 'store']);
    Route::get('/jabatan_kegiatan/{id}/show', [JabatanKegiatanController::class, 'show'])->name('jabatan_kegiatan.show');

    // Kegiatan Routes
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan/list', [KegiatanController::class, 'list']);
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->name('kegiatan.show');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{id}/update', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::get('/kegiatan/{id}/delete', [KegiatanController::class, 'confirm'])->name('kegiatan.delete');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'delete'])->name('kegiatan.destroy');
    Route::post('/kegiatan/import', [KegiatanController::class, 'import'])->name('kegiatan.import');

    // Detail Kegiatan Routes
    Route::get('/detail_kegiatan', [DetailKegiatanController::class, 'index'])->name('detail_kegiatan.index');
    Route::post('/detail_kegiatan/list', [DetailKegiatanController::class, 'list']);
    Route::get('/detail_kegiatan/{id_kegiatan}', [DetailKegiatanController::class, 'show'])->name('detail_kegiatan.show');
    Route::get('/detail_kegiatan/{id}/edit', [DetailKegiatanController::class, 'edit'])->name('detail_kegiatan.edit');
    Route::put('/detail_kegiatan/{id}/update', [DetailKegiatanController::class, 'update'])->name('detail_kegiatan.update');
    Route::post('/detail_kegiatan/import', [DetailKegiatanController::class, 'import'])->name('detail_kegiatan.import');

    Route::get('/kegiatan_eksternal', [KegiatanEksternalController::class, 'create'])->name('kegiatan_eksternal.create'); 
    Route::post('/kegiatan_eksternal', [KegiatanEksternalController::class, 'store'])->name('kegiatan_eksternal.store'); 
    
    Route::get('/', [AgendaKegiatanController::class, 'index'])->name('index'); // Menampilkan daftar agenda
    Route::get('agenda/create_ajax', [AgendaKegiatanController::class, 'create_ajx'])->name('create_ajax'); // Form tambah agenda
    Route::post('/store', [AgendaKegiatanController::class, 'store'])->name('store'); // Proses simpan agenda
    Route::get('/{id}/show', [AgendaKegiatanController::class, 'show'])->name('show'); // Tampilkan detail agenda
    Route::get('/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('edit'); // Form edit agenda
    Route::put('/{id}/update', [AgendaKegiatanController::class, 'update'])->name('update'); // Proses update agenda
    Route::delete('/{id}/delete', [AgendaKegiatanController::class, 'destroy'])->name('destroy'); // Proses hapus agenda
    Route::get('agenda/export_excel', [AgendaKegiatanController::class, 'export_excel'])->name('export_excel'); // Ekspor agenda
    Route::post('agenda/import', [AgendaKegiatanController::class, 'import'])->name('import'); // Impor agenda
    Route::post('agenda/list', [AgendaKegiatanController::class, 'list'])->name('list'); // Mengambil daftar agenda (AJAX misalnya)

