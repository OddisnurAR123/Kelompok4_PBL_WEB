<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
=======
use App\Http\Controllers\JenisPenggunaController;
use App\Models\JenisPenggunaModel;
use App\Http\Controllers\JenisKegiatanController;
use App\Http\Controllers\KegiatanController;
>>>>>>> 5e01e390ee3762ab93b32465318cd6838d0e5545
use App\Http\Controllers\KategoriKegiatanController;
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

<<<<<<< HEAD
// Daftar route agenda
Route::get('/agenda', [AgendaKegiatanController::class, 'index']);
=======

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
>>>>>>> 5e01e390ee3762ab93b32465318cd6838d0e5545

    // Route to fetch the list of agendas (with filtering support)
    Route::post('/agenda/list', [AgendaKegiatanController::class, 'list']);

    // Route to view the create agenda form (ajax)
    Route::get('/agenda/create', [AgendaKegiatanController::class, 'create'])->name('agenda.create');

    // Route to store a new agenda via ajax
    Route::post('/agenda/store', [AgendaKegiatanController::class, 'store']);

    // Route to view a single agenda details
    Route::get('/agenda/{id}/show', [AgendaKegiatanController::class, 'show'])->name('agenda.show');

    // Route to show the edit form for a specific agenda
    Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('agenda.edit');

    // Route to update a specific agenda
    Route::put('/agenda/{id}/update', [AgendaKegiatanController::class, 'update']);

    // Route to confirm the deletion of an agenda
    Route::get('/agenda/{id}/delete', [AgendaKegiatanController::class, 'confirm_delete'])->name('agenda.delete');

    // Route to delete a specific agenda
    Route::delete('/agenda/{id}/delete', [AgendaKegiatanController::class, 'destroy']);

    // Route for importing agendas via ajax
    Route::post('/agenda/import', [AgendaKegiatanController::class, 'import'])->name('agenda.import');

    // Route for exporting agenda data to Excel
    Route::get('/agenda/export_excel', [AgendaKegiatanController::class, 'export_excel'])->name('agenda.export_excel');

    // Route for exporting agenda data to PDF
    Route::get('/agenda/export_pdf', [AgendaKegiatanController::class, 'export_pdf'])->name('agenda.export_pdf');