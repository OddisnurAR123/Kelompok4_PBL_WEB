<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DetailKegiatanModel;
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

    // Menampilkan data detail kegiatan dalam bentuk json untuk DataTables
    public function list(Request $request) {
        $detail_kegiatan = DetailKegiatanModel::with('kegiatan:id_kegiatan,nama_kegiatan')
        ->select('id_detail_kegiatan', 'id_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja')
        ->get();    

        return DataTables::of($detail_kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($detail_kegiatan) {
                $btn = '<button onclick="modalAction(\''.route('detail_kegiatan.show', $detail_kegiatan->id).'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.route('detail_kegiatan.edit', $detail_kegiatan->id).'\')" class="btn btn-warning btn-sm">Edit</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan detail detail kegiatan
    public function show(string $id) {
        $detail_kegiatan = DetailKegiatanModel::find($id);

        if (!$detail_kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ]);
        }

        return view('kegiatan.show', ['kegiatan' => $detail_kegiatan]);
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