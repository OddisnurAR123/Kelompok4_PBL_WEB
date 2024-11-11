<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
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

// Daftar route agenda
Route::get('/agenda', [AgendaKegiatanController::class, 'index']);

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