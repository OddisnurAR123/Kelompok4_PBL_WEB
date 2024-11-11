<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPenggunaController;
use App\Models\JenisPenggunaModel;
use App\Http\Controllers\KategoriKegiatanController;

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

    Route::get('/kategori-kegiatan', [KategoriKegiatanController::class, 'index']);
    Route::post('/kategori-kegiatan/list', [KategoriKegiatanController::class, 'list']);
    Route::get('/kategori-kegiatan/create', [KategoriKegiatanController::class, 'create']);
    Route::post('/kategori-kegiatan', [KategoriKegiatanController::class, 'store']);
    Route::get('/kategori-kegiatan/create_ajax', [KategoriKegiatanController::class, 'create_ajax']);
    Route::post('/kategori-kegiatan/store_ajax', [KategoriKegiatanController::class, 'store_ajax']);
    Route::get('/kategori-kegiatan/{id}', [KategoriKegiatanController::class, 'show']);
    Route::get('/kategori-kegiatan/{id}/show_ajax', [KategoriKegiatanController::class, 'show_ajax']);
    Route::get('/kategori-kegiatan/{id}/edit', [KategoriKegiatanController::class, 'edit']);
    Route::put('/kategori-kegiatan/{id}', [KategoriKegiatanController::class, 'update']);
    Route::get('/kategori-kegiatan/{id}/edit_ajax', [KategoriKegiatanController::class, 'edit_ajax']);
    Route::put('/kategori-kegiatan/{id}/update_ajax', [KategoriKegiatanController::class, 'update_ajax']);
    Route::get('/kategori-kegiatan/{id}/delete_ajax', [KategoriKegiatanController::class, 'confirm_ajax']);
    Route::delete('/kategori-kegiatan/{id}/delete_ajax', [KategoriKegiatanController::class, 'delete_ajax']);
    Route::delete('/kategori-kegiatan/{id}', [KategoriKegiatanController::class, 'destroy']);
    Route::get('/kategori-kegiatan/import', [KategoriKegiatanController::class, 'import']);
    Route::post('/kategori-kegiatan/import_ajax', [KategoriKegiatanController::class, 'import_ajax']);
    Route::get('/kategori-kegiatan/export_excel', [KategoriKegiatanController::class, 'export_excel']);
    Route::get('/kategori-kegiatan/export_pdf', [KategoriKegiatanController::class, 'export_pdf']);


