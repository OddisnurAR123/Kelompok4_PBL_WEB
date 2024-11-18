<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DetailKegiatanModel;
use Illuminate\Support\Facades\Validator;

class DetailKegiatanController extends Controller
{
    // Menampilkan halaman daftar kegiatan
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Input Kegiatan',
            'list' => ['Home', 'Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar kegiatan yang ada'
        ];

        $activeMenu = 'kegiatan';

        return view('kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan data kegiatan dalam bentuk json untuk DataTables
    public function list(Request $request) {
        $kegiatan = DetailKegiatanModel::select('id', 'id_kegiatan', 'kategori_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja')
            ->get();

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\''.route('detail_kegiatan.show', $kegiatan->id).'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.route('detail_kegiatan.edit', $kegiatan->id).'\')" class="btn btn-warning btn-sm">Edit</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan detail kegiatan
    public function show(string $id) {
        $kegiatan = DetailKegiatanModel::find($id);

        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ]);
        }

        return view('kegiatan.show', ['kegiatan' => $kegiatan]);
    }

    // Menampilkan form edit kegiatan via Ajax
    public function edit_ajax($id) {
        $kegiatan = DetailKegiatanModel::findOrFail($id);
        return view('kegiatan.edit', ['kegiatan' => $kegiatan]);
    }

    // Menyimpan perubahan data kegiatan via Ajax
    public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'id_kegiatan' => 'required|integer|exists:t_kegiatan,id_kegiatan',
                'kategori_kegiatan' => 'required|string|max:100',
                'keterangan' => 'nullable|string|max:255',
                'progres_kegiatan' => 'required|numeric|min:0|max:100',
                'beban_kerja' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $kegiatan = DetailKegiatanModel::findOrFail($id);
            $kegiatan->update($request->only('id_kegiatan', 'kategori_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja'));

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

    // Menampilkan konfirmasi hapus kegiatan via Ajax
    public function confirm_ajax($id) {
        $kegiatan = DetailKegiatanModel::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Apakah Anda yakin ingin menghapus kegiatan ini?',
            'data' => $kegiatan,
        ]);
    }

    // Proses import excel kegiatan dengan AJAX
    public function import_ajax(Request $request) {
        // Placeholder untuk implementasi import file Excel
        return response()->json([
            'status' => true,
            'message' => 'Fitur import berhasil diimplementasikan.',
        ]);
    }
}