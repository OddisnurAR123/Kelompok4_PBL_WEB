<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\JenisPenggunaModel;
use Illuminate\Support\Facades\Validator;

class JenisPenggunaController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Pengguna',
            'list' => ['Home', 'Jenis Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar semua jenis pengguna'
        ];

        $activeMenu = 'jenis_pengguna';

        return view('jenis_pengguna.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) {
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'kode_jenis_pengguna', 'nama_jenis_pengguna');

        return DataTables::of($jenisPengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenisPengguna) {
                $btn = '<button onclick="modalAction(\''.url('/jenis_pengguna/' . $jenisPengguna->id_jenis_pengguna . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jenis_pengguna/' . $jenisPengguna->id_jenis_pengguna . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jenis_pengguna/' . $jenisPengguna->id_jenis_pengguna . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create() {
        return view('jenis_pengguna.create');
    }

    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jenis_pengguna' => 'required|string|unique:m_jenis_pengguna,kode_jenis_pengguna',
                'nama_jenis_pengguna' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            JenisPenggunaModel::create([
                'kode_jenis_pengguna' => $request->kode_jenis_pengguna,
                'nama_jenis_pengguna' => $request->nama_jenis_pengguna,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data jenis pengguna berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    public function show(string $id) {
        $jenisPengguna = JenisPenggunaModel::find($id);

        if (!$jenisPengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('jenis_pengguna.show', ['jenisPengguna' => $jenisPengguna]);
    }

    public function edit(string $id) {
        $jenisPengguna = JenisPenggunaModel::find($id);

        if (!$jenisPengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('jenis_pengguna.edit', ['jenisPengguna' => $jenisPengguna]);
    }

    public function update(Request $request, string $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jenis_pengguna' => 'required|string|unique:m_jenis_pengguna,kode_jenis_pengguna,' . $id . ',id_jenis_pengguna',
                'nama_jenis_pengguna' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $jenisPengguna = JenisPenggunaModel::find($id);

            if ($jenisPengguna) {
                $jenisPengguna->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diperbarui',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }

    public function delete(string $id) {
        if (request()->ajax() || request()->wantsJson()) {
            $jenisPengguna = JenisPenggunaModel::find($id);

            if ($jenisPengguna) {
                $jenisPengguna->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }
}
