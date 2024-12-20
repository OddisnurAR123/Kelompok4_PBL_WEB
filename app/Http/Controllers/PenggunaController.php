<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use App\Models\JenisPenggunaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class PenggunaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Dashboard', 'Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar pengguna yang ada'
        ];

        $activeMenu = 'pengguna';

        $jenisPengguna = JenisPenggunaModel::all();

        return view('pengguna.index',[
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenisPengguna' => $jenisPengguna,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $pengguna = PenggunaModel::select(
                'id_pengguna',
                'id_jenis_pengguna', 
                'nama_pengguna',
                'username', 
                'password', 
                'nip',
                'email', 
            )->with('jenisPengguna');
            if ($request->id_jenis_pengguna) {
                $pengguna->where('id_jenis_pengguna', $request->id_jenis_pengguna);
            }
        return DataTables::of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                $btn = '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/show').'\')" class="btn btn-info btn-sm">';
                $btn .= '<i class="fas fa-eye"></i></button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/edit').'\')" class="btn btn-warning btn-sm">';
                $btn .= '<i class="fas fa-edit"></i></button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/delete').'\')" class="btn btn-danger btn-sm">';
                $btn .= '<i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['foto', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();
        return view('pengguna.create', compact('jenisPengguna'));
    }

    public function store(Request $request)
    {
        $rules = [
            'id_jenis_pengguna' => 'required|integer',
            'nama_pengguna' => 'required|string|max:100',
            'username' => 'required|string|unique:m_pengguna,username|max:50',
            'password' => 'required|string|min:6',
            'nip' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        PenggunaModel::create([
            'id_jenis_pengguna' => $request->id_jenis_pengguna,
            'nama_pengguna' => $request->nama_pengguna,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nip' => $request->nip,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil disimpan.',
        ]);
    }

    public function edit($id_pengguna)
    {
        $pengguna = PenggunaModel::find($id_pengguna);
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();

        return view('pengguna.edit', compact('pengguna', 'jenisPengguna'));
    }

    public function update(Request $request, $id_pengguna)
    {
        $rules = [
            'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
            'nama_pengguna' => 'required|string',
            'username' => 'required|string|unique:m_pengguna,username,' . $id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:6',
            'nip' => 'required|string',
            'email' => 'required|email|unique:m_pengguna,email,' . $id_pengguna . ',id_pengguna',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $pengguna = PenggunaModel::find($id_pengguna);
        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $pengguna->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pengguna = PenggunaModel::find($id);
            if ($pengguna) {
                $pengguna->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
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
            public function confirm(string $id)
            {
                // Ambil user berdasarkan ID dan sertakan relasi dengan LevelModel
                $pengguna = PenggunaModel::with('jenisPengguna')->find($id);
        
                // Pastikan user ditemukan
                if (!$pengguna) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User tidak ditemukan.'
                    ]);
                }
        
                // Kirimkan data user ke view
                return view('pengguna.confirm', ['pengguna' => $pengguna]);
            }
            public function edit_profile()
            {
                return view('profil.profil', ['user' => auth()->user()]);
            }
        
            public function export_pdf()
            {
                // Mengambil data pengguna beserta jenis_pengguna
                $pengguna = PenggunaModel::with('jenisPengguna')
                    ->select('id_pengguna', 'id_jenis_pengguna', 'nama_pengguna', 'username', 'password', 'nip', 'email')
                    ->get();
            
                try {
                    // Load view untuk membuat PDF
                    $pdf = Pdf::loadView('pengguna.export_pdf', ['pengguna' => $pengguna]);
            
                    $pdf->setPaper('a4', 'portrait');
                    $pdf->setOption("isRemoteEnabled", true);
            
                    return $pdf->stream('Data_Pengguna_' . date('Y-m-d_H-i-s') . '.pdf');
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()]);
                }
            }
            
}


