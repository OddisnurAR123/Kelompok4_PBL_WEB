<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPenggunaController;
use App\Models\JenisPenggunaModel;
use App\Http\Controllers\JenisKegiatanController;
use App\Http\Controllers\KegiatanController;

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

Route::get('/', [WelcomeController::class, 'index']);

// Route::middleware(['authorize:ADM,MNG'])->group(function () {
    Route::get('/jenis_pengguna', [JenisPenggunaController::class, 'index'])->name('jenis_pengguna.index');  
    // Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']);
    // Route::post('/level/store_ajax', [LevelController::class, 'store_ajax']);
    Route::get('/jenis_pengguna/{id}/show', [JenisPenggunaModel::class, 'show']);
    // Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
    // Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']);
    // Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
    // Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
    // Route::post('/level/import_ajax', [LevelController::class, 'import_ajax']);
    // Route::get('/level/export_excel', [LevelController::class, 'export_excel']);
    // Route::get('/level/export_pdf', [LevelController::class, 'export_pdf']);
// });

    Route::get('/jenis_kegiatan', [JenisKegiatanController::class, 'index']);
    Route::post('/jenis_kegiatan/list', [JenisKegiatanController::class, 'list']);
    Route::get('/jenis_kegiatan/create', [JenisKegiatanController::class, 'create']);
    Route::post('/jenis_kegiatan', [JenisKegiatanController::class, 'store']);
    Route::get('/jenis_kegiatan/create_ajax', [JenisKegiatanController::class, 'create_ajax']);
    Route::post('/jenis_kegiatan/store_ajax', [JenisKegiatanController::class, 'store_ajax']);
    Route::get('/jenis_kegiatan/{id}', [JenisKegiatanController::class, 'show']);
    Route::get('/jenis_kegiatan/{id}/show_ajax', [JenisKegiatanController::class, 'show_ajax']);
    Route::get('/jenis_kegiatan/{id}/edit', [JenisKegiatanController::class, 'edit']);
    Route::put('/jenis_kegiatan/{id}', [JenisKegiatanController::class, 'update']);
    Route::get('/jenis_kegiatan/{id}/edit_ajax', [JenisKegiatanController::class, 'edit_ajax']);
    Route::put('/jenis_kegiatan/{id}/update_ajax', [JenisKegiatanController::class, 'update_ajax']);
    Route::get('/jenis_kegiatan/{id}/delete_ajax', [JenisKegiatanController::class, 'confirm_ajax']);
    Route::delete('/jenis_kegiatan/{id}/delete_ajax', [JenisKegiatanController::class, 'delete_ajax']);
    Route::delete('/jenis_kegiatan/{id}', [JenisKegiatanController::class, 'destroy']);
    Route::get('/jenis_kegiatan/import', [JenisKegiatanController::class, 'import']);
    Route::post('/jenis_kegiatan/import_ajax', [JenisKegiatanController::class, 'import_ajax']);
    Route::get('/jenis_kegiatan/export_excel', [JenisKegiatanController::class, 'export_excel']);
    Route::get('/jenis_kegiatan/export_pdf', [JenisKegiatanController::class, 'export_pdf']);

    // Kegiatan Routes
    Route::prefix('kegiatan')->group(function () {
        Route::get('/', [KegiatanController::class, 'index']); // Menampilkan halaman daftar kegiatan
        Route::post('list', [KegiatanController::class, 'getList']); // Menampilkan data kegiatan dalam bentuk JSON (untuk DataTable)
        Route::get('create', [KegiatanController::class, 'create']); // Menampilkan form untuk tambah kegiatan
        Route::post('store', [KegiatanController::class, 'store']); // Menyimpan kegiatan baru
        Route::get('edit/{id}', [KegiatanController::class, 'edit']); // Menampilkan form untuk edit kegiatan
        Route::post('update/{id}', [KegiatanController::class, 'update']); // Mengupdate kegiatan
        Route::delete('delete/{id}', [KegiatanController::class, 'destroy']); // Menghapus kegiatan
        Route::get('export_excel', [KegiatanController::class, 'exportExcel']); // Mengekspor data kegiatan ke format XLSX
        Route::get('export_pdf', [KegiatanController::class, 'exportPdf']); // Mengekspor data kegiatan ke format PDF
        Route::get('import', [KegiatanController::class, 'import']); // Menampilkan form untuk impor kegiatan
        Route::post('import', [KegiatanController::class, 'importStore']); // Menyimpan data kegiatan yang diimpor
    });

