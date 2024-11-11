<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPenggunaController;
use App\Models\JenisPenggunaModel;
use App\Http\Controllers\JenisKegiatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\JabatanKegiatanController;
use App\Http\Controllers\AgendaKegiatanController;


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

// Daftar route agenda
Route::get('/agenda', [AgendaKegiatanController::class, 'index']);

// Route::middleware(['authorize:ADM,MNG'])->group(function () {
    Route::get('/jenis_pengguna', [JenisPenggunaController::class, 'index'])->name('jenis_pengguna.index');  
    Route::post('/jenis_pengguna/list', [JenisPenggunaController::class, 'list']);
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

    Route::get('/jenis_kegiatan', [JenisKegiatanController::class, 'index'])->name('jenis_kegiatan.index');  
    Route::post('/jenis_kegiatan/list', [JenisKegiatanController::class, 'list']);

    Route::get('/jabatan_kegiatan', [JabatanKegiatanController::class, 'index'])->name('jabatan_kegiatan.index');  
    Route::post('/jabatan_kegiatan/list', [JabatanKegiatanController::class, 'list']);

    // Kegiatan Routes
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan/list', [KegiatanController::class, 'list']);
    Route::get('kegiatan/{id_kegiatan}', [KegiatanController::class, 'show'])->name('kegiatan.show');
    Route::get('/kegiatan/create_ajax', [KegiatanController::class, 'create_ajax'])->name('kegiatan.create_ajax');
    Route::post('/kegiatan/ajax', [KegiatanController::class, 'store_ajax'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit_ajax'])->name('kegiatan.edit');
    Route::put('/kegiatan/{id}/update', [KegiatanController::class, 'update_ajax'])->name('kegiatan.update');
    Route::get('/kegiatan/{id}/delete', [KegiatanController::class, 'confirm_ajax'])->name('kegiatan.delete');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'delete_ajax'])->name('kegiatan.destroy');
    Route::post('/kegiatan/import', [KegiatanController::class, 'import_ajax'])->name('kegiatan.import');

    Route::get('/agenda', [AgendaKegiatanController::class, 'index']);
    Route::post('/agenda/list', [AgendaKegiatanController::class, 'list']);
    Route::get('/agenda/create', [AgendaKegiatanController::class, 'create'])->name('agenda.create');
    Route::post('/agenda/store', [AgendaKegiatanController::class, 'store']);
    Route::get('/agenda/{id}/show', [AgendaKegiatanController::class, 'show'])->name('agenda.show');
    Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{id}/update', [AgendaKegiatanController::class, 'update']);
    Route::get('/agenda/{id}/delete', [AgendaKegiatanController::class, 'confirm_delete'])->name('agenda.delete');
    Route::delete('/agenda/{id}/delete', [AgendaKegiatanController::class, 'destroy']);
    Route::post('/agenda/import', [AgendaKegiatanController::class, 'import'])->name('agenda.import');
    Route::get('/agenda/export_excel', [AgendaKegiatanController::class, 'export_excel'])->name('agenda.export_excel');
