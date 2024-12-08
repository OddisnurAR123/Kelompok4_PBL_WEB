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
use App\Http\Controllers\DetailAgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DraftSuratTugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TampilKegiatanController;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KinerjaDosenController;


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
// Pattern untuk parameter ID harus berupa angka
Route::pattern('id', '[0-9]+');

// Routes untuk autentikasi login
Route::get('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

//route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function(){
    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM'])->group(function(){
        //route data master jenis pengguna
        Route::get('/jenis_pengguna', [JenisPenggunaController::class, 'index']);
        Route::post('/jenis_pengguna/list', [JenisPenggunaController::class, 'list']);
        Route::get('/jenis_pengguna/{id}/show', [JenisPenggunaController::class, 'show']);
        Route::get('/jenis_pengguna/create', [JenisPenggunaController::class, 'create']);
        Route::post('/jenis_pengguna/store', [JenisPenggunaController::class, 'store']);
        Route::get('/jenis_pengguna/{id}/edit', [JenisPenggunaController::class, 'edit']);
        Route::post('/jenis_pengguna/{id}/update', [JenisPenggunaController::class, 'update']);
        Route::get('/jenis_pengguna/{id}/delete', [JenisPenggunaController::class, 'delete']);

        //route data master kategori pengguna
        Route::get('/kategori_kegiatan', [KategoriKegiatanController::class, 'index'])->name('kategori_kegiatan.index');
        Route::post('/kategori_kegiatan/list', [KategoriKegiatanController::class, 'list']);
        Route::get('kategori_kegiatan/{id}/show', [KategoriKegiatanController::class, 'show'])->name('kategori_kegiatan.show');
        Route::get('/kategori_kegiatan/create', [KategoriKegiatanController::class, 'create']); 
        Route::post('/kategori_kegiatan', [KategoriKegiatanController::class, 'store'])->name('kategori_kegiatan.store');
        Route::get('/kategori_kegiatan/{id}/edit', [KategoriKegiatanController::class, 'edit'])->name('kategori_kegiatan.edit');
        Route::put('/kategori_kegiatan/{id}/update', [KategoriKegiatanController::class, 'update']);
        Route::get('/kategori_kegiatan/{id}/delete', [KategoriKegiatanController::class, 'confirm']);
        Route::delete('/kategori_kegiatan/{id}/delete', [KategoriKegiatanController::class, 'delete']);
        
        //route data master jabatan kegiatan
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

        //route data master pengguna
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

        //route kegiatan
        Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create'); //adm
        Route::post('/kegiatan/store', [KegiatanController::class, 'store'])->name('kegiatan.store'); //adm
        Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit'); //adm
        Route::put('/kegiatan/update/{id_kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update'); //adm
        Route::get('/kegiatan/{id}/delete', [KegiatanController::class, 'confirm'])->name('kegiatan.delete'); //adm
        Route::delete('/kegiatan/{id}', [KegiatanController::class, 'delete'])->name('kegiatan.destroy'); //adm
        Route::get('/kegiatan/import', [KegiatanController::class, 'import'])->name('kegiatan.import'); //adm
        Route::post('/kegiatan/import_ajax', [KegiatanController::class, 'import_ajax'])->name('kegiatan.import.ajax'); //adm
        Route::get('/kegiatan/export_excel', [KegiatanController::class, 'export_excel'])->name('kegiatan.export.excel'); //adm
        Route::get('/kegiatan/export_pdf', [KegiatanController::class, 'export_pdf'])->name('kegiatan.export.pdf'); //adm

        //route draft surat tugas
        Route::get('/draft_surat_tugas', [DraftSuratTugasController::class, 'index'])->name('draft_surat_tugas.index'); //adm
        Route::post('/draft_surat_tugas/list', [DraftSuratTugasController::class, 'list']); //adm
        Route::get('/draft_surat_tugas/{id}/show', [DraftSuratTugasController::class, 'show'])->name('draft_surat_tugas.show'); //adm
        Route::get('/draft_surat_tugas/create', [DraftSuratTugasController::class, 'create']); //adm
        Route::post('/draft_surat_tugas/store', [DraftSuratTugasController::class, 'store'])->name('draft_surat_tugas.store');   //adm
        Route::get('/draft_surat_tugas/{id}/edit', [DraftSuratTugasController::class, 'edit'])->name('draft_surat_tugas.edit'); //adm
        Route::put('/draft_surat_tugas/{id}/update', [DraftSuratTugasController::class, 'update']); //adm
        Route::get('/draft_surat_tugas/{id}/delete', [DraftSuratTugasController::class, 'confirm']); //adm
        Route::delete('/draft_surat_tugas/{id}/delete', [DraftSuratTugasController::class, 'delete']); //adm

    });

    Route::middleware(['authorize:ADM, PIM, DSN'])->group(function(){
        
        //route profil
        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.profil');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

        //route kegiatan
        Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::post('/kegiatan/list', [KegiatanController::class, 'list']);
        Route::get('/kegiatan/{id}/show', [KegiatanController::class, 'show'])->name('kegiatan.show'); //semua

        //route statistik kinerja
        Route::get('/kinerja-dosen', [KinerjaDosenController::class, 'index'])->name('kinerja.dosen'); //semua

    });
    
    Route::middleware(['authorize:DSN'])->group(function(){
        //route detail kegiatan
        Route::get('/detail_kegiatan', [DetailKegiatanController::class, 'index'])->name('detail_kegiatan.index'); //semua pic
        Route::post('/detail_kegiatan/list', [DetailKegiatanController::class, 'list']); //semua pic
        Route::get('/detail_kegiatan/create', [DetailKegiatanController::class, 'create'])->name('detail_kegiatan.create'); //semua pic
        Route::post('/detail_kegiatan/store', [DetailKegiatanController::class, 'store'])->name('detail_kegiatan.store'); //semua pic
        Route::get('/detail_kegiatan/{id}/show', [DetailKegiatanController::class, 'show'])->name('detail_kegiatan.show'); //dosen
        Route::get('/detail_kegiatan/{id}/edit', [DetailKegiatanController::class, 'edit'])->name('detail_kegiatan.edit'); //dosen
        Route::put('/detail_kegiatan/update/{id_detail_kegiatan}', [DetailKegiatanController::class, 'update'])->name('detail_kegiatan.update'); //dosen
        Route::get('/detail_kegiatan/export_excel', [DetailKegiatanController::class, 'export_excel'])->name('detail_kegiatan.export_excel'); //dosen
        Route::get('/detail_kegiatan/export_pdf', [DetailKegiatanController::class, 'export_pdf'])->name('detail_kegiatan.export_pdf'); //dosen

    // Detail Kegiatan Routes
    Route::get('/detail_kegiatan', [DetailKegiatanController::class, 'index'])->name('detail_kegiatan.index');
    Route::post('/detail_kegiatan/list', [DetailKegiatanController::class, 'list']);
    Route::get('/detail_kegiatan/create', [DetailKegiatanController::class, 'create'])->name('detail_kegiatan.create');
    Route::post('/detail_kegiatan/store', [DetailKegiatanController::class, 'store'])->name('detail_kegiatan.store');
    Route::get('/detail_kegiatan/{id}/show', [DetailKegiatanController::class, 'show'])->name('detail_kegiatan.show');
    Route::get('/detail_kegiatan/{id}/edit', [DetailKegiatanController::class, 'edit'])->name('detail_kegiatan.edit');
    Route::put('/detail_kegiatan/update/{id_detail_kegiatan}', [DetailKegiatanController::class, 'update'])->name('detail_kegiatan.update');
    Route::get('/detail_kegiatan/export_excel', [DetailKegiatanController::class, 'export_excel'])->name('detail_kegiatan.export_excel');
    Route::get('/detail_kegiatan/export_pdf', [DetailKegiatanController::class, 'export_pdf'])->name('detail_kegiatan.export_pdf');

    // Detail Agenda Routes
    Route::get('/detail_agenda', [DetailAgendaController::class, 'index'])->name('detail_agenda.index');
    Route::post('/detail_agenda/list', [DetailAgendaController::class, 'list']);
    Route::get('/detail_agenda/create', [DetailAgendaController::class, 'create'])->name('detail_agenda.create');
    Route::post('/detail_agenda/store', [DetailAgendaController::class, 'store'])->name('detail_agenda.store');
    Route::get('/detail_agenda/{id}/show', [DetailAgendaController::class, 'show'])->name('detail_agenda.show');
    Route::get('/detail_agenda/{id}/edit', [DetailAgendaController::class, 'edit'])->name('detail_agenda.edit');
    Route::put('/detail_agenda/update/{id_detail_agenda}', [DetailAgendaController::class, 'update'])->name('detail_agenda.update');
    Route::get('/detail_agenda/export_excel', [DetailAgendaController::class, 'export_excel'])->name('detail_agenda.export_excel');
    Route::get('/detail_agenda/export_pdf', [DetailAgendaController::class, 'export_pdf'])->name('detail_agenda.export_pdf');
       


        //route detail kegiatan
        Route::get('/detail_agenda', [DetailAgendaController::class, 'index'])->name('detail_agenda.index'); //dos agt

        //route agenda
        Route::get('/agenda', [AgendaKegiatanController::class, 'index'])->name('agenda.index'); //dos
        Route::post('/agenda/list', [AgendaKegiatanController::class, 'list']); //dos
        Route::get('/agenda/{id}/show', [AgendaKegiatanController::class, 'show'])->name('agenda.show'); //dos
        Route::get('/agenda/create', [AgendaKegiatanController::class, 'create']); //dos
        Route::post('/agenda/store', [AgendaKegiatanController::class, 'store'])->name('agenda.store');  //dos  
        Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('agenda.edit'); //dos
        Route::put('/agenda/{id}/update', [AgendaKegiatanController::class, 'update']); //dos
        Route::get('/agenda/{id}/delete', [AgendaKegiatanController::class, 'confirm'])->name('agenda.confirm'); //dos
        Route::delete('/agenda/{id}/delete', [AgendaKegiatanController::class, 'delete'])->name('agenda.delete'); //dos
        Route::post('/agenda/import', [KegiatanController::class, 'import'])->name('agenda.import'); //dos
        Route::get('/agenda/export_excel', [KegiatanController::class, 'export_excel'])->name('agenda.export_excel'); //dos
        Route::get('/agenda/export_pdf', [KegiatanController::class, 'export_pdf'])->name('agenda.export_pdf'); //dos
    });

    Route::middleware(['authorize:DSN, PIM'])->group(function(){ 
        //route notifikasi
        Route::get('/', [WelcomeController::class, 'index'])->name('dashboard');
        Route::get('/notifikasi', [NotifikasiController::class, 'getNotifications']);

    Route::get('/agenda', [AgendaKegiatanController::class, 'index'])->name('agenda.index');
    Route::post('/agenda/list', [AgendaKegiatanController::class, 'list']);
    Route::get('/agenda/{id}/show', [AgendaKegiatanController::class, 'show'])->name('agenda.show');
    Route::get('/agenda/create', [AgendaKegiatanController::class, 'create']);
    Route::get('/agenda/getPengguna', [AgendaKegiatanController::class, 'getPengguna'])->name('agenda.getPengguna');
    Route::post('/agenda/store', [AgendaKegiatanController::class, 'store'])->name('agenda.store');
    Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{id}/update', [AgendaKegiatanController::class, 'update'])->name('agenda.update');
    Route::get('/agenda/{id}/delete', [AgendaKegiatanController::class, 'confirm'])->name('agenda.confirm');
    Route::delete('/agenda/{id}/delete', [AgendaKegiatanController::class, 'delete'])->name('agenda.delete');
    Route::post('/agenda/import', [KegiatanController::class, 'import'])->name('agenda.import');
    Route::get('/agenda/export_excel', [KegiatanController::class, 'export_excel'])->name('agenda.export_excel');
    Route::get('/agenda/export_pdf', [KegiatanController::class, 'export_pdf'])->name('agenda.export_pdf');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna/list', [PenggunaController::class, 'list']);
    Route::get('/pengguna/{id}/show', [PenggunaController::class, 'show'])->name('pengguna.show');
    Route::get('/pengguna/create', [PenggunaController::class, 'create']);
    Route::post('/pengguna/store', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{id}/update', [PenggunaController::class, 'update']);
    Route::get('/pengguna/{id}/delete', [PenggunaController::class, 'confirm']);
    Route::delete('/pengguna/{id}/delete', [PenggunaController::class, 'delete']);    
    Route::post('/pengguna/import_ajax', [PenggunaController::class, 'import_ajax'])->name('pengguna.import.ajax');
    Route::get('/pengguna/export_excel', [PenggunaController::class, 'export_excel'])->name('pengguna.export.excel');
    Route::get('/pengguna/export_pdf', [PenggunaController::class, 'export_pdf'])->name('pengguna.export.pdf');

        //route kegiatan non jti
        Route::get('/kegiatan_eksternal/create', [KegiatanEksternalController::class, 'create'])->name('kegiatan_eksternal.create'); //semua
        Route::post('/kegiatan_eksternal/store', [KegiatanEksternalController::class, 'store'])->name('kegiatan_eksternal.store'); //semua
        Route::get('/kegiatan_eksternal', [KegiatanEksternalController::class, 'index'])->name('kegiatan_eksternal.index');
        Route::post('kegiatan_eksternal/list', [KegiatanEksternalController::class, 'list']);
    });

    // Route logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
