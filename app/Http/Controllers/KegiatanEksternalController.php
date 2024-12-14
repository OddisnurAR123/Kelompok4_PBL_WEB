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
        $user = auth()->user();
        $idJenisPengguna = $user->id_jenis_pengguna;
        
        // Ambil data sesuai hak akses pengguna
        if (in_array($idJenisPengguna, [1, 2])) {
            $kegiatanEksternal = KegiatanEksternalModel::select('id_kegiatan_eksternal', 'nama_kegiatan', 'waktu_kegiatan', 'periode', 'pic');
        } elseif ($idJenisPengguna == 3) {
            $kegiatanEksternal = KegiatanEksternalModel::select('id_kegiatan_eksternal', 'nama_kegiatan', 'waktu_kegiatan', 'periode')
                ->where('pic', $user->nama_pengguna);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Hak akses tidak valid.'
            ], 403);
        }

        // Tampilkan data dengan konfigurasi kolom sesuai hak akses
        return DataTables::of($kegiatanEksternal)
            ->make(true);
    }

    public function create() {
        return view('kegiatan_eksternal.create');
    }

    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
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
    
            $namaPic = auth()->user()->nama_pengguna ?? 'Unknown';
            $idPengguna = auth()->user()->id_pengguna ?? null;  // Menyimpan id_pengguna yang diambil dari pengguna yang sedang login
    
            try {
                // Menyimpan data dengan menambahkan id_pengguna
                KegiatanEksternalModel::create([
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'waktu_kegiatan' => $request->waktu_kegiatan,
                    'pic' => $namaPic,
                    'periode' => $request->periode,
                    'id_pengguna' => $idPengguna, // Menyimpan id_pengguna
                    'id_kategori_kegiatan' => 3,
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