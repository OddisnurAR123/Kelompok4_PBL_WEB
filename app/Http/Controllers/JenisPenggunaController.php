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
                $btn = '<button onclick="modalAction(\''.url('/jenis_pengguna/' . $jenisPengguna->id_jenis_pengguna . '/show').'\')" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $jenisPengguna = JenisPenggunaModel::find($id);
    
        // Jika data tidak ditemukan, kembalikan respons error
        if (!$jenisPengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }
    
        // Jika data ditemukan, tampilkan view
        return view('jenis_pengguna.show', ['jenisPengguna' => $jenisPengguna]);
    }

    // Mengambil data PIC (id_jenis_pengguna = 3)
    public function getPic()
    {
        $pic = JenisPenggunaModel::where('id_jenis_pengguna', 3)->get(['id', 'nama_pengguna']);
        return response()->json([
            'status' => true,
            'data' => $pic
        ]);
    }

    // Mengambil data Anggota (id_jenis_pengguna != 3)
    public function getAnggota()
    {
        $anggota = JenisPenggunaModel::where('id_jenis_pengguna', '!=', 3)->get(['id', 'nama_pengguna']);
        return response()->json([
            'status' => true,
            'data' => $anggota
        ]);
    }
}
