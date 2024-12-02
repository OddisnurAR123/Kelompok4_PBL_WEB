<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DetailKegiatanModel;
use App\Models\KegiatanModel;
use Illuminate\Support\Facades\Validator;

class DetailKegiatanController extends Controller
{
    // Menampilkan halaman daftar detail kegiatan
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Detail Kegiatan',
            'list' => ['Home', 'detail_kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar Progres kegiatan yang ada'
        ];

        $activeMenu = 'detail_kegiatan';

        return view('detail_kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) {
        $detail_kegiatan = DetailKegiatanModel::with('kegiatan:id_kegiatan,nama_kegiatan')
            ->select('id_detail_kegiatan', 'id_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja')
            ->get();    
    
        return DataTables::of($detail_kegiatan)
            ->addIndexColumn()
            ->addColumn('kegiatan', function ($detail_kegiatan) {
                // Menampilkan nama_kegiatan yang terkait dengan id_kegiatan
                return $detail_kegiatan->kegiatan ? $detail_kegiatan->kegiatan->nama_kegiatan : 'Tidak ada';
            })
            ->addColumn('aksi', function ($detail_kegiatan) {
                $btn = '<button onclick="window.location.href=\''.route('detail_kegiatan.show', ['id' => $detail_kegiatan->id_detail_kegiatan]).'\'" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\''.route('detail_kegiatan.edit', ['id' => $detail_kegiatan->id_detail_kegiatan]).'\')" class="btn btn-warning btn-sm">Edit</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Progres Kegiatan',
            'list' => ['Home', 'Progres Kegiatan', 'Tambah Progres Kegiatan']
        ];

        // Ambil data kegiatan untuk ditampilkan di dropdown
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan')->get();

        return view('detail_kegiatan.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
            'keterangan' => 'required|string|max:100',
            'progres_kegiatan' => 'required|numeric|min:0|max:100',
            'beban_kerja' => 'required|in:Ringan,Sedang,Berat',
        ], [
            'id_kegiatan.required' => 'Kegiatan harus dipilih.',
            'id_kegiatan.exists' => 'Kegiatan tidak valid.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'keterangan.max' => 'Keterangan maksimal 100 karakter.',
            'progres_kegiatan.required' => 'Progres kegiatan harus diisi.',
            'progres_kegiatan.numeric' => 'Progres kegiatan harus berupa angka.',
            'progres_kegiatan.min' => 'Progres kegiatan minimal 0.',
            'progres_kegiatan.max' => 'Progres kegiatan maksimal 100.',
            'beban_kerja.required' => 'Beban kerja harus dipilih.',
            'beban_kerja.in' => 'Pilihan beban kerja tidak valid.',
        ]);

        try {
            // Simpan data ke database
            DetailKegiatanModel::create([
                'id_kegiatan' => $request->id_kegiatan,
                'keterangan' => $request->keterangan,
                'progres_kegiatan' => $request->progres_kegiatan,
                'beban_kerja' => $request->beban_kerja,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Detail kegiatan berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan detail detail kegiatan
    public function show($id)
    {
        
        $detail_kegiatan = DetailKegiatanModel::with('kegiatan')->find($id);
        
        if (!$detail_kegiatan) {
            return view('detail_kegiatan.show', compact('detail_kegiatan'))->with('error', 'Data tidak ditemukan');
        }

        return view('detail_kegiatan.show', compact('detail_kegiatan'));
    }

    // Menampilkan form edit detail kegiatan via Ajax
    public function edit_ajax($id) {
        $detail_kegiatan = DetailKegiatanModel::findOrFail($id);
        return view('kegiatan.edit', ['kegiatan' => $detail_kegiatan]);
    }

    // Menyimpan perubahan data detail kegiatan via Ajax
    public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'id_kegiatan' => 'required|integer|exists:t_kegiatan,id_kegiatan',
                'keterangan' => 'nullable|string|max:255',
                'progress_kegiatan' => 'required|numeric|min:0|max:100',
                'beban_kerja' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $detail_kegiatan = DetailKegiatanModel::findOrFail($id);
            $detail_kegiatan->update($request->only('id_kegiatan', 'kategori_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja'));

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Request bukan AJAX.',
        ]);
    }

    // Menampilkan konfirmasi hapus detail kegiatan via Ajax
    public function confirm_ajax($id) {
        $detail_kegiatan = DetailKegiatanModel::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Apakah Anda yakin ingin menghapus kegiatan ini?',
            'data' => $detail_kegiatan,
        ]);
    }

    // Proses import excel detail  kegiatan dengan AJAX
    public function import_ajax(Request $request) {
        // Placeholder untuk implementasi import file Excel
        return response()->json([
            'status' => true,
            'message' => 'Fitur import berhasil diimplementasikan.',
        ]);
    }
}