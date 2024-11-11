<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\JenisKegiatanModel;
use Illuminate\Support\Facades\Validator;

class JenisKegiatanController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Kegiatan',
            'list' => ['Home', 'Jenis    Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar jenis kegiatan yang ada'
        ];

        $activeMenu = 'kategori_kegiatan';

        return view('kategori_kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) {
        $jenisPengguna = JenisKegiatanModel::select('id_kategori_kegiatan', 'kode_kategori_kegiatan', 'nama_kategori_kegiatan');

        return JenisKegiatanModel::of($jenisPengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenisPengguna) {
                $btn = '<button onclick="modalAction(\''.url('/kategori_kegiatan/' . $jenisPengguna->id_kategori_kegiatan . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori_kegiatan/' . $jenisPengguna->id_kategori_kegiatan . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori_kegiatan/' . $jenisPengguna->id_kategori_kegiatan . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('kategori_kegiatan.create');
    }

    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori_kegiatan' => 'required|string|min:3|unique:m_kategori_kegiatan,kode_kategori_kegiatan',
                'nama_kategori_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            JenisKegiatanModel::create([
                'kode_kategori_kegiatan' => $request->kode_kategori_kegiatan,
                'nama_kategori_kegiatan' => $request->nama_kategori_kegiatan,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data jenis pengguna berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    public function edit(string $id)
    {
        $jenisPengguna = JenisKegiatanModel::find($id);

        if (!$jenisPengguna) {
            return response()->json(['status' => false, 'message' => 'Data jenis pengguna tidak ditemukan']);
        }

        return view('kategori_kegiatan.edit', ['jenisPengguna' => $jenisPengguna]);
    }

    public function update(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori_kegiatan' => 'required|string|max:20|unique:m_kategori_kegiatan,kode_kategori_kegiatan,' . $id . ',id_kategori_kegiatan',
                'nama_kategori_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $jenisPengguna = JenisKegiatanModel::find($id);

            if ($jenisPengguna) {
                $jenisPengguna->update($request->all());
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
            $jenisPengguna = JenisKegiatanModel::find($id);

            if ($jenisPengguna) {
                $jenisPengguna->delete();
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
        $jenisPengguna = JenisKegiatanModel::find($id);

        if (!$jenisPengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis Pengguna tidak ditemukan.'
            ]);
        }

        return view('kategori_kegiatan.show', ['jenisPengguna' => $jenisPengguna]);
    }
}
