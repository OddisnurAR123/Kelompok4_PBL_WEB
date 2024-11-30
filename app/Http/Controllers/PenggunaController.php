<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use App\Models\JenisPenggunaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar pengguna yang ada'
        ];

        $activeMenu = 'pengguna';

        return view('pengguna.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $pengguna = PenggunaModel::with('jenisPengguna')
            ->select(
                'id_pengguna',
                'nama_pengguna',
                'username',
                'email',
                'foto_profil',
                'id_jenis_pengguna'
            );

        return DataTables::of($pengguna)
            ->addIndexColumn()
            ->addColumn('id_jenis_pengguna', function ($pengguna) {
                return $pengguna->id_jenis_pengguna->nama_jenis_pengguna ?? '-';
            })
            ->addColumn('foto', function ($pengguna) {
                return '<img src="' . $pengguna->foto_profil . '" class="img-fluid img-thumbnail" width="50">';
            })
            ->addColumn('aksi', function ($pengguna) {
                $btn = '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['foto', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $jenisPengguna = JenisPenggunaModel::all(); // Ambil data jenis pengguna dari database
        return view('pengguna.create', compact('jenisPengguna'));
    }    

    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk input 
            $rules = [
            'nama_pengguna' => 'required|string',
            'username' => 'required|string|unique:m_pengguna,username',
            'email' => 'required|email|unique:m_pengguna,email',
            'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
            'password' => 'required|string|min:6',
            'foto_profil' => 'nullable|image|max:2048',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ], 400);
        }

        try {
            $fotoPath = $request->file('foto_profil') 
                ? $request->file('foto_profil')->store('pengguna', 'public') 
                : null;

            PenggunaModel::create([
                'nama_pengguna' => $request->nama_pengguna,
                'username' => $request->username,
                'email' => $request->email,
                'id_jenis_pengguna' => $request->id_jenis_pengguna,
                'password' => $request->password,
                'foto_profil' => $fotoPath,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data pengguna berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
public function edit(string $id_pengguna) {
        $pengguna = PenggunaModel::find($id_pengguna);

        if (!$pengguna) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        return view('pengguna.edit', compact('jenisPengguna'));
    }
    public function update(Request $request, $id_pengguna) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
            'nama_pengguna' => 'required|string',
            'username' => 'required|string|unique:m_pengguna,username',
            'email' => 'required|email|unique:m_pengguna,email',
            'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
            'password' => 'required|string|min:6',
            'foto_profil' => 'nullable|image|max:2048',
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $pengguna = PenggunaModel::find($id_pengguna);

            if ($pengguna) {
                $pengguna->update($request->all());
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

    public function show(string $id)
    {
        $pengguna = PenggunaModel::find($id);
    
        // Jika data tidak ditemukan, kembalikan respons error
        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }
    
        // Jika data ditemukan, tampilkan view
        return view('pengguna.show', ['pengguna' => $pengguna]);
    }

}
