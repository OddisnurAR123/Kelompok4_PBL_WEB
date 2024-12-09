<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanEksternalModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class KegiatanEksternalController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Kegiatan Non-JTI',
            'list' => ['Dashboard', 'Kegiatan Non-JTI']
        ];

        $page = (object) [
            'title' => 'Daftar kegiatan yang ada'
        ];

        $activeMenu = 'kegiatan_eksternal';

        return view('kegiatan_eksternal.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request) {
        // Mengambil data kegiatan eksternal dengan nama kolom yang benar
        $kegiatanEksternal = KegiatanEksternalModel::select('id_kegiatan_eksternal', 'nama_kegiatan', 'waktu_kegiatan', 'periode');

        // Menampilkan data dalam bentuk DataTables tanpa kolom aksi
        return DataTables::of($kegiatanEksternal)
            ->make(true); // Mengaktifkan DataTables
    }     

    // Menampilkan form tambah kegiatan eksternal
    public function create() {
        return view('kegiatan_eksternal.create');
    }

    // Menyimpan data kegiatan eksternal ke dalam tabel t_kegiatan_eksternal
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $rules = [
                'nama_kegiatan' => 'required|string|max:255',
                'waktu_kegiatan' => 'required|date',
                'periode' => 'required|string|max:4',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            // Nama pengguna yang sedang login
            $nama_pic = auth()->user()->nama_pengguna ?? 'Unknown';
    
            try {
                // Simpan data ke database
                KegiatanEksternalModel::create([
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'waktu_kegiatan' => $request->waktu_kegiatan,
                    'pic' => $nama_pic,
                    'periode' => $request->periode,
                    'id_kategori_kegiatan' => 3, // Asumsi ID kategori kegiatan
                ]);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori kegiatan berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ]);
            }
        }
    
        return redirect('/');
    }
        
}
