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

        $activeMenu = 'jabatan_kegiatan';

        return view('jabatan_kegiatan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request) {
        $jabatanKegiatan = JabatanKegiatanModel::select('id_jabatan_kegiatan', 'kode_jabatan_kegiatan', 'nama_jabatan_kegiatan', 'is_pic', 'urutan');
    
        return DataTables::of($jabatanKegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jabatanKegiatan) {
                $btn = '<button onclick="modalAction(\''.url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/show').'\')" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/edit').'\')" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/delete').'\')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }     
    
    public function create()
    {
        return view('jabatan_kegiatan.create');  
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi umum
            $rules = [
                'kode_jabatan_kegiatan' => 'required|string|min:3|unique:m_jabatan_kegiatan,kode_jabatan_kegiatan',
                'nama_jabatan_kegiatan' => 'required|string|max:100',
                'is_pic' => 'required|in:0,1',
                'urutan' => 'required|integer',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            // Cek validasi awal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            // Validasi tambahan untuk is_pic
            if ($request->is_pic == 1) {
                $existingPic = JabatanKegiatanModel::where('is_pic', 1)->first();
                if ($existingPic) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sudah ada data dengan is_pic bernilai 1.',
                    ]);
                }
            }
    
            // Validasi tambahan untuk urutan
            $existingOrder = JabatanKegiatanModel::where('urutan', $request->urutan)->first();
            if ($existingOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dengan urutan ini sudah ada.',
                ]);
            }
    
            // Simpan data jika semua validasi terpenuhi
            JabatanKegiatanModel::create([
                'kode_jabatan_kegiatan' => $request->kode_jabatan_kegiatan,
                'nama_jabatan_kegiatan' => $request->nama_jabatan_kegiatan,
                'is_pic' => $request->is_pic,
                'urutan' => $request->urutan,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data jabatan kegiatan berhasil disimpan.',
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
                'urutan' => 'required|integer',
                'is_pic' => 'required|boolean',
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
                // Validasi tambahan untuk is_pic
                if ($request->is_pic == 1) {
                    $existingPic = JabatanKegiatanModel::where('is_pic', 1)->where('id_jabatan_kegiatan', '!=', $id)->first();
                    if ($existingPic) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Sudah ada data dengan is_pic bernilai 1.',
                        ]);
                    }
                }
    
                // Validasi tambahan untuk urutan
                $existingOrder = JabatanKegiatanModel::where('urutan', $request->urutan)->where('id_jabatan_kegiatan', '!=', $id)->first();
                if ($existingOrder) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data dengan urutan ini sudah ada.',
                    ]);
                }
    
                // Update data
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

    public function confirm(string $id)
    {
        $jabatanKegiatan = JabatanKegiatanModel::find($id);
    
        // Jika data level tidak ditemukan, kirimkan respon error
        if (!$jabatanKegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Jabatan Kegiatan tidak ditemukan.'
            ]);
        }
    
        // Kembalikan view konfirmasi penghapusan level
        return view('jabatan_kegiatan.confirm', ['jabatanKegiatan' => $jabatanKegiatan]);
    }

}
