<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KegiatanModel; // Gantilah dengan model yang sesuai untuk Kegiatan
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
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

    public function list(Request $request) {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'kode_kegiatan', 'nama_kegiatan');

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\''.url('/kegiatan/' . $kegiatan->id_kegiatan . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kegiatan/' . $kegiatan->id_kegiatan . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kegiatan/' . $kegiatan->id_kegiatan . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('kegiatan.create');
    }

    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kegiatan' => 'required|string|min:3|unique:m_kegiatan,kode_kegiatan',
                'nama_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            KegiatanModel::create([
                'kode_kegiatan' => $request->kode_kegiatan,
                'nama_kegiatan' => $request->nama_kegiatan,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    public function edit(string $id)
    {
        $kegiatan = KegiatanModel::find($id);

        if (!$kegiatan) {
            return response()->json(['status' => false, 'message' => 'Data kegiatan tidak ditemukan']);
        }

        return view('kegiatan.edit', ['kegiatan' => $kegiatan]);
    }

    public function update(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kegiatan' => 'required|string|max:20|unique:m_kegiatan,kode_kegiatan,' . $id . ',id_kegiatan',
                'nama_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $kegiatan = KegiatanModel::find($id);

            if ($kegiatan) {
                $kegiatan->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }

        return redirect('/');
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kegiatan = KegiatanModel::find($id);

            if ($kegiatan) {
                $kegiatan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }

        return redirect('/');
    }

    public function show(string $id)
    {
        $kegiatan = KegiatanModel::find($id);

        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ]);
        }

        return view('kegiatan.show', ['kegiatan' => $kegiatan]);
    }
}