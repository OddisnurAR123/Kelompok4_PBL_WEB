<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KategoriKegiatanModel;
use Illuminate\Support\Facades\Validator;

class KategoriKegiatanController extends Controller
{
    // Tampilkan halaman index
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori Kegiatan',
            'list' => ['Home', 'Kategori Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar kategori kegiatan yang ada'
        ];

        $activeMenu = 'kategori_kegiatan';

        return view('kategori_kegiatan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // List data untuk DataTables
    // public function list(Request $request)
    // {
    //     $kategoriKegiatan = KategoriKegiatanModel::select('id_kategori_kegiatan', 'kode_kategori_kegiatan', 'nama_kategori_kegiatan');
    
    //     return DataTables::of($kategoriKegiatan)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function($row) {
    //             return '
    //                 <button onclick="modalAction(\''.url('kategori_kegiatan/', $row->id_kategori_kegiatan, '/show').'\')" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></button>
    //                 <button onclick="modalAction(\''.url('kategori_kegiatan.edit', $row->id_kategori_kegiatan).'\')" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></button>
    //                 <button onclick="deleteData(\''.url('kategori_kegiatan.delete', $row->id_kategori_kegiatan).'\')" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></button>
    //             ';
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }

    public function list(Request $request) {
        $kategoriKegiatan = KategoriKegiatanModel::select('id_kategori_kegiatan', 'kode_kategori_kegiatan', 'nama_kategori_kegiatan');
    
        return DataTables::of($kategoriKegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategoriKegiatan) {
                $btn = '<button onclick="modalAction(\''.url('/kategori_kegiatan/' . $kategoriKegiatan->id_kategori_kegiatan . '/show').'\')" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori_kegiatan/' . $kategoriKegiatan->id_kategori_kegiatan . '/edit').'\')" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori_kegiatan/' . $kategoriKegiatan->id_kategori_kegiatan . '/delete').'\')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Tampilkan halaman create
    public function create() {
        return view('kategori_kegiatan.create');
    }

    // Simpan data baru
    public function store(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk input 
            $rules = [
                'kode_kategori_kegiatan' => 'required|unique:m_kategori_kegiatan',
                'nama_kategori_kegiatan' => 'required',
            ];
    
            // Melakukan validasi
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                // Mengembalikan respon jika validasi gagal
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
                        // Menyimpan data 
                        KategoriKegiatanModel::create([
                            'kode_kategori_kegiatan' => $request->kode_kategori_kegiatan,
                            'nama_kategori_kegiatan' => $request->nama_kategori_kegiatan,
                        ]);
                
                        // Mengembalikan respon sukses
                        return response()->json([
                            'status' => true,
                            'message' => 'Data kategori kegiatan berhasil disimpan',
                        ]);
                    }
                
                    return redirect('/');
        }

    // Tampilkan halaman edit
    public function edit(string $id_kategori_kegiatan) {
        $kategoriKegiatan = KategoriKegiatanModel::find($id_kategori_kegiatan);

        if (!$kategoriKegiatan) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        return view('kategori_kegiatan.edit', compact('kategoriKegiatan'));
    }

    // Update data
    public function update(Request $request, $id_kategori_kegiatan) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori_kegiatan' => 'required|string|max:20|unique:m_kategori_kegiatan,kode_kategori_kegiatan,' . $id_kategori_kegiatan . ',id_kategori_kegiatan',
                'nama_kategori_kegiatan' => 'required|string|max:100',
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

            $kategoriKegiatan = KategoriKegiatanModel::find($id_kategori_kegiatan);

            if ($kategoriKegiatan) {
                $kategoriKegiatan->update($request->all());
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

    // Detail data
    public function show(string $id)
    {
        $kategoriKegiatan = KategoriKegiatanModel::find($id);
    
        // Jika data tidak ditemukan, kembalikan respons error
        if (!$kategoriKegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }
    
        // Jika data ditemukan, tampilkan view
        return view('kategori_kegiatan.show', ['kategoriKegiatan' => $kategoriKegiatan]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategoriKegiatan = KategoriKegiatanModel::find($id);

            if ($kategoriKegiatan) {
                $kategoriKegiatan->delete();
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
        $kategoriKegiatan = KategoriKegiatanModel::find($id);
    
        // Jika data level tidak ditemukan, kirimkan respon error
        if (!$kategoriKegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori Kegiatan tidak ditemukan.'
            ]);
        }
    
        // Kembalikan view konfirmasi penghapusan level
        return view('kategori_kegiatan.confirm', ['kategoriKegiatan' => $kategoriKegiatan]);
    }
    
}
