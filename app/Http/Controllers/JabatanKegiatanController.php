<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\JabatanKegiatanModel;  // Gunakan model JabatanKegiatanModel
use Illuminate\Support\Facades\Validator;

class JabatanKegiatanController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Jabatan Kegiatan',
            'list' => ['Home', 'Jabatan Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar jabatan kegiatan yang ada'
        ];

        $activeMenu = 'jabatan_kegiatan';  // Ubah menjadi jabatan_kegiatan

        return view('jabatan_kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $jabatanKegiatan = JabatanKegiatanModel::select('id_jabatan_kegiatan', 'kode_jabatan_kegiatan', 'nama_jabatan_kegiatan');
        
        return DataTables::of($jabatanKegiatan)
            ->addColumn('aksi', function ($jabatanKegiatan) {
                $btn = '<button onclick="modalAction(\''.url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }        
    
    public function create()
    {
        return view('jabatan_kegiatan.create');  
    }

    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jabatan_kegiatan' => 'required|string|min:3|unique:m_jabatan_kegiatan,kode_jabatan_kegiatan',
                'nama_jabatan_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            JabatanKegiatanModel::create([
                'kode_jabatan_kegiatan' => $request->kode_jabatan_kegiatan,
                'nama_jabatan_kegiatan' => $request->nama_jabatan_kegiatan,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data jabatan kegiatan berhasil disimpan',
            ]);
        }

        return redirect('/');
    }
  
    public function show(string $id) {
        $jabatanKegiatan = JabatanKegiatanModel::find($id);

        if (!$jabatanKegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('jabatan_kegiatan.show', ['jabatanKegiatan' => $jabatanKegiatan]);
    }

    public function edit(string $id)
    {
        $jabatanKegiatan = JabatanKegiatanModel::find($id);

        if (!$jabatanKegiatan) {
            return response()->json(['status' => false, 'message' => 'Data jabatan kegiatan tidak ditemukan']);
        }

        return view('jabatan_kegiatan.edit', ['jabatanKegiatan' => $jabatanKegiatan]);
    }

    public function update(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            // Sesuaikan dengan kolom yang ada
            $rules = [
                'kode_jabatan_kegiatan' => 'required|string|max:20|unique:m_jabatan_kegiatan,kode_jabatan_kegiatan,' . $id . ',id_jabatan_kegiatan',
                'nama_jabatan_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $jabatanKegiatan = JabatanKegiatanModel::find($id);

            if ($jabatanKegiatan) {
                $jabatanKegiatan->update($request->all());
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
            $jabatanKegiatan = JabatanKegiatanModel::find($id);

            if ($jabatanKegiatan) {
                $jabatanKegiatan->delete();
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

}
