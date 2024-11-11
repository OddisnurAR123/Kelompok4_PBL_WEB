<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriKegiatanController;
use App\Http\Controllers\AgendaController;


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

    // Daftar route agenda
    Route::prefix('agenda')->group(function() {
    
        // Menampilkan halaman utama agenda
        Route::get('/', [AgendaController::class, 'index'])->name('agenda.index');
        
        // Menyimpan agenda baru
        Route::post('store', [AgendaController::class, 'store'])->name('agenda.store');
    
        // Menampilkan form untuk mengedit agenda
        Route::get('edit/{id}', [AgendaController::class, 'edit'])->name('agenda.edit');
        
        // Mengupdate agenda
        Route::post('update/{id}', [AgendaController::class, 'update'])->name('agenda.update');
        
        // Menghapus agenda
        Route::delete('destroy/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
    
        // Menampilkan detail agenda berdasarkan id
        Route::get('show/{id}', [AgendaController::class, 'show'])->name('agenda.show');
    
        // Menyediakan file untuk export agenda dalam format Excel
        Route::get('export_excel', [AgendaController::class, 'exportExcel'])->name('agenda.export_excel');
        
        // Menyediakan file untuk export agenda dalam format PDF
        Route::get('export_pdf', [AgendaController::class, 'exportPDF'])->name('agenda.export_pdf');
        
        // Menampilkan form import agenda
        Route::get('import', [AgendaController::class, 'showImportForm'])->name('agenda.import');
        
        // Proses import agenda dari file
        Route::post('import', [AgendaController::class, 'import'])->name('agenda.import.store');
        
        // AJAX untuk menampilkan data agenda dalam DataTable
        Route::post('list', [AgendaController::class, 'list'])->name('agenda.list');
    });
    