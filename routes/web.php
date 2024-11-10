<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPenggunaController;
use App\Models\JenisPenggunaModel;
use App\Http\Controllers\KategoriKegiatanController;
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

    Route::get('/kategori-kegiatan', [KategoriKegiatanController::class, 'index']);
    // Route::post('/kategori-kegiatan/list', [KategoriKegiatanController::class, 'list']);
    // Route::get('/kategori-kegiatan/create', [KategoriKegiatanController::class, 'create']);
    // Route::post('/kategori-kegiatan', [KategoriKegiatanController::class, 'store']);
    // Route::get('/kategori-kegiatan/create_ajax', [KategoriKegiatanController::class, 'create_ajax']);
    // Route::post('/kategori-kegiatan/store_ajax', [KategoriKegiatanController::class, 'store_ajax']);
    // Route::get('/kategori-kegiatan/{id}', [KategoriKegiatanController::class, 'show']);
    // Route::get('/kategori-kegiatan/{id}/show_ajax', [KategoriKegiatanController::class, 'show_ajax']);
    // Route::get('/kategori-kegiatan/{id}/edit', [KategoriKegiatanController::class, 'edit']);
    // Route::put('/kategori-kegiatan/{id}', [KategoriKegiatanController::class, 'update']);
    // Route::get('/kategori-kegiatan/{id}/edit_ajax', [KategoriKegiatanController::class, 'edit_ajax']);
    // Route::put('/kategori-kegiatan/{id}/update_ajax', [KategoriKegiatanController::class, 'update_ajax']);
    // Route::get('/kategori-kegiatan/{id}/delete_ajax', [KategoriKegiatanController::class, 'confirm_ajax']);
    // Route::delete('/kategori-kegiatan/{id}/delete_ajax', [KategoriKegiatanController::class, 'delete_ajax']);
    // Route::delete('/kategori-kegiatan/{id}', [KategoriKegiatanController::class, 'destroy']);
    // Route::get('/kategori-kegiatan/import', [KategoriKegiatanController::class, 'import']);
    // Route::post('/kategori-kegiatan/import_ajax', [KategoriKegiatanController::class, 'import_ajax']);
    // Route::get('/kategori-kegiatan/export_excel', [KategoriKegiatanController::class, 'export_excel']);
    // Route::get('/kategori-kegiatan/export_pdf', [KategoriKegiatanController::class, 'export_pdf']);