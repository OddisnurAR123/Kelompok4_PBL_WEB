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

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');


//route yang memerlukan autentikasi
// Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [WelcomeController::class, 'index']);
        
    // // Routes untuk halaman profil
    // Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show'); // Menampilkan profil pengguna
    // Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // Halaman form edit profil
    // Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update'); // Proses pembaruan profil

    // // Routes untuk ganti password
    // Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword'); // Halaman form ganti password
    // Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword'); // Proses pembaruan password

    Route::middleware(['auth'])->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show'); // Tambahan jika ada halaman profil
    });

    // // Route untuk menampilkan halaman profil
    // Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');

    // // Route untuk menampilkan halaman edit profil
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    //route kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan/list', [KegiatanController::class, 'list']);
    Route::get('/kegiatan/{id}/show', [KegiatanController::class, 'show'])->name('kegiatan.show');
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create'); 
    Route::post('/kegiatan/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/update/{id_kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::get('/kegiatan/{id}/delete', [KegiatanController::class, 'confirm'])->name('kegiatan.confirm');
    Route::delete('/kegiatan/{id}/delete', [KegiatanController::class, 'delete'])->name('kegiatan.delete');
    Route::get('/kegiatan/export_pdf', [KegiatanController::class, 'export_pdf'])->name('kegiatan.export.pdf');
    Route::get('kegiatan/{id_kegiatan}/unduh_surat', [KegiatanController::class, 'unduhSuratTugas'])->name('kegiatan.downloadDraft');
    // Route untuk menampilkan form upload
    Route::get('/kegiatan/{id}/upload_surat_tugas', [KegiatanController::class, 'showUploadForm'])->name('kegiatan.uploadForm');
    // Route untuk menangani upload file surat tugas
    Route::post('/kegiatan/{id}/upload_surat_tugas', [KegiatanController::class, 'upload'])->name('kegiatan.upload');
    // Route untuk mengunduh file surat tugas
    //Route::get('kegiatan/data', [KegiatanController::class, 'getData'])->name('kegiatan.data');
    // Route untuk mengunduh surat tugas
    //Route::get('kegiatan/{id}/unduh', [KegiatanController::class, 'suratTugas'])->name('kegiatan.unduh');
    // Route untuk menampilkan surat tugas
    Route::get('kegiatan/{id}/surat-tugas', [KegiatanController::class, 'suratTugas'])->name('kegiatan.surat_tugas');
    // web.php
    Route::get('kegiatan_pimpinan', [KegiatanController::class, 'index'])->name('index');
    Route::post('/kegiatan_pimpinan/list', [KegiatanController::class, 'list']);
    // Route::get('progress_chart/{id}', [KegiatanController::class, 'getProgressKegiatan'])->name('progress_chart');



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
    Route::get('/get-average-progress', [DetailKegiatanController::class, 'getAverageProgress'])->name('get.averageProgress');


    // Detail Agenda Routes
    Route::get('/detail_agenda', [DetailAgendaController::class, 'index'])->name('detail_agenda.index');
    Route::post('/detail_agenda/list', [DetailAgendaController::class, 'list']);
    Route::get('/detail_agenda/create/{id_kegiatan}/{id_agenda}', [DetailAgendaController::class, 'create'])->name('detail_agenda.create');
    Route::post('/detail_agenda/store', [DetailAgendaController::class, 'store'])->name('detail_agenda.store');
    Route::get('/detail_agenda/{id}/show', [DetailAgendaController::class, 'show'])->name('detail_agenda.show');
    Route::get('/detail_agenda/{id}/edit', [DetailAgendaController::class, 'edit'])->name('detail_agenda.edit');
    Route::put('/detail_agenda/update/{id_detail_agenda}', [DetailAgendaController::class, 'update'])->name('detail_agenda.update');
    Route::get('/detail_agenda/export_excel', [DetailAgendaController::class, 'export_excel'])->name('detail_agenda.export_excel');
    Route::get('/detail_agenda/export_pdf', [DetailAgendaController::class, 'export_pdf'])->name('detail_agenda.export_pdf');
    Route::get('/detail_agenda/upgrade/{id_kegiatan}/{id_agenda}', [DetailAgendaController::class, 'upgrade'])->name('detail_agenda.upgrade');
    
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

    Route::get('/draft_surat_tugas', [DraftSuratTugasController::class, 'index'])->name('draft_surat_tugas.index');
    Route::post('/draft_surat_tugas/list', [DraftSuratTugasController::class, 'list']);
    Route::get('/draft_surat_tugas/{id}/show', [DraftSuratTugasController::class, 'show'])->name('draft_surat_tugas.show');
    Route::get('/draft_surat_tugas/create', [DraftSuratTugasController::class, 'create']);
    Route::post('/draft_surat_tugas/store', [DraftSuratTugasController::class, 'store'])->name('draft_surat_tugas.store');
    Route::get('/draft_surat_tugas/{id}/edit', [DraftSuratTugasController::class, 'edit'])->name('draft_surat_tugas.edit');
    Route::put('/draft_surat_tugas/{id}/update', [DraftSuratTugasController::class, 'update']);
    Route::get('/draft_surat_tugas/{id}/delete', [DraftSuratTugasController::class, 'confirm']);
    Route::delete('/draft_surat_tugas/{id}/delete', [DraftSuratTugasController::class, 'delete']);

    //route agenda
    Route::get('/agenda', [AgendaKegiatanController::class, 'index'])->name('agenda.index');
    Route::post('/agenda/list', [AgendaKegiatanController::class, 'list']);
    Route::get('/agenda/{id}/show', [AgendaKegiatanController::class, 'show'])->name('agenda.show');
    Route::get('agenda/create', [AgendaKegiatanController::class, 'create'])->name('agenda.create');
    Route::get('agenda/get-pengguna', [AgendaKegiatanController::class, 'getPengguna'])->name('agenda.getPengguna');
    Route::post('agenda/store', [AgendaKegiatanController::class, 'store'])->name('agenda.store');
    Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{id}/update', [AgendaKegiatanController::class, 'update'])->name('agenda.update');
    Route::get('/agenda/{id}/delete', [AgendaKegiatanController::class, 'confirm'])->name('agenda.confirm');
    Route::delete('/agenda/{id}/delete', [AgendaKegiatanController::class, 'delete'])->name('agenda.delete');
    Route::post('/agenda/import', [KegiatanController::class, 'import'])->name('agenda.import');
    Route::get('/agenda/export_excel', [KegiatanController::class, 'export_excel'])->name('agenda.export_excel');
    Route::get('/agenda/export_pdf', [KegiatanController::class, 'export_pdf'])->name('agenda.export_pdf');
    Route::get('/get-agenda-by-kegiatan/{id}', [AgendaKegiatanController::class, 'getAgendaByKegiatan']);


    //route statistik kinerja
    Route::get('/kinerja-dosen', [KinerjaDosenController::class, 'index'])->name('kinerja_dosen.index');

        //route data master jenis pengguna
        Route::get('/jenis_pengguna', [JenisPenggunaController::class, 'index']);
        Route::post('/jenis_pengguna/list', [JenisPenggunaController::class, 'list']);
        Route::get('/jenis_pengguna/{id}/show', [JenisPenggunaController::class, 'show']);
        Route::get('/jenis_pengguna/create', [JenisPenggunaController::class, 'create']);
        Route::post('/jenis_pengguna/store', [JenisPenggunaController::class, 'store']);
        Route::get('/jenis_pengguna/{id}/edit', [JenisPenggunaController::class, 'edit']);
        Route::post('/jenis_pengguna/{id}/update', [JenisPenggunaController::class, 'update']);
        Route::get('/jenis_pengguna/{id}/delete', [JenisPenggunaController::class, 'delete']);
        Route::post('/pengguna/import_ajax', [PenggunaController::class, 'import_ajax'])->name('pengguna.import.ajax');
        Route::get('/pengguna/export_excel', [PenggunaController::class, 'export_excel'])->name('pengguna.export.excel');
        Route::get('/pengguna/export_pdf', [PenggunaController::class, 'export_pdf'])->name('pengguna.export.pdf');
   

        //route draft surat tugas
        Route::get('/draft_surat_tugas', [DraftSuratTugasController::class, 'index'])->name('draft_surat_tugas.index');
        Route::post('/draft_surat_tugas/list', [DraftSuratTugasController::class, 'list']);
        Route::get('/draft_surat_tugas/{id}/show', [DraftSuratTugasController::class, 'show'])->name('draft_surat_tugas.show');
        Route::get('/draft_surat_tugas/create', [DraftSuratTugasController::class, 'create']);
        Route::post('/draft_surat_tugas/store', [DraftSuratTugasController::class, 'store'])->name('draft_surat_tugas.store');
        Route::get('/draft_surat_tugas/{id}/edit', [DraftSuratTugasController::class, 'edit'])->name('draft_surat_tugas.edit');
        Route::put('/draft_surat_tugas/{id}/update', [DraftSuratTugasController::class, 'update']);
        Route::get('/draft_surat_tugas/{id}/delete', [DraftSuratTugasController::class, 'confirm']);
        Route::delete('/draft_surat_tugas/{id}/delete', [DraftSuratTugasController::class, 'delete']);
        
    
    
        //route notifikasi
        Route::get('/', [WelcomeController::class, 'index'])->name('dashboard');
        Route::get('/notifikasi', [NotifikasiController::class, 'getNotifications']);


        //route kegiatan non jti
        Route::get('/kegiatan_eksternal', [KegiatanEksternalController::class, 'index'])->name('kegiatan_eksternal.index');

        Route::post('/kegiatan_eksternal/list', [KegiatanEksternalController::class, 'list']);
        Route::get('/kegiatan_eksternal/create', [KegiatanEksternalController::class, 'create'])->name('kegiatan_eksternal.create');
        Route::post('/kegiatan_eksternal/store', [KegiatanEksternalController::class, 'store'])->name('kegiatan_eksternal.store'); 

    // Route logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');