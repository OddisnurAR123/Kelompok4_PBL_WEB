<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\JenisKegiatanModel;
use Illuminate\Support\Facades\Validator;

class JenisKegiatanController extends Controller
{
    // Tampilkan halaman index
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Kegiatan',
            'list' => ['Home', 'Jenis Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar jenis kegiatan yang ada'
        ];

        $activeMenu = 'kategori_kegiatan';

        return view('jenis_kegiatan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // List data untuk DataTables
    // public function list(Request $request)
    // {
    //     $jenisKegiatan = JenisKegiatanModel::select('id_kategori_kegiatan', 'kode_kategori_kegiatan', 'nama_kategori_kegiatan');
    
    //     return DataTables::of($jenisKegiatan)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function($row) {
    //             return '
    //                 <button onclick="modalAction(\''.url('jenis_kegiatan/', $row->id_kategori_kegiatan, '/show').'\')" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></button>
    //                 <button onclick="modalAction(\''.url('jenis_kegiatan.edit', $row->id_kategori_kegiatan).'\')" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></button>
    //                 <button onclick="deleteData(\''.url('jenis_kegiatan.delete', $row->id_kategori_kegiatan).'\')" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></button>
    //             ';
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }

    public function list(Request $request) {
        $jenisKegiatan = JenisKegiatanModel::select('id_kategori_kegiatan', 'kode_kategori_kegiatan', 'nama_kategori_kegiatan');
    
        return DataTables::of($jenisKegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenisKegiatan) {
                $btn = '<button onclick="modalAction(\''.url('/jenis_kegiatan/' . $jenisKegiatan->id_kategori_kegiatan . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jenis_kegiatan/' . $jenisKegiatan->id_kategori_kegiatan . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jenis_kegiatan/' . $jenisKegiatan->id_kategori_kegiatan . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Tampilkan halaman create
    public function create() {
        return view('jenis_kegiatan.create');
    }

    // Simpan data baru
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'kode_kategori_kegiatan' => 'required|string|min:3|unique:jenis_kegiatan,kode_kategori_kegiatan',
            'nama_kategori_kegiatan' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        JenisKegiatanModel::create($request->only(['kode_kategori_kegiatan', 'nama_kategori_kegiatan']));

        return response()->json(['status' => true, 'message' => 'Data berhasil disimpan.']);
    }

    // Tampilkan halaman edit
    public function edit($id_kategori_kegiatan) {
        $jenisKegiatan = JenisKegiatanModel::find($id_kategori_kegiatan);

        if (!$jenisKegiatan) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        return view('jenis_kegiatan.edit', compact('jenisKegiatan'));
    }

    // Update data
    public function update(Request $request, $id_kategori_kegiatan) {
        $jenisKegiatan = JenisKegiatanModel::find($id_kategori_kegiatan);

        if (!$jenisKegiatan) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        $validator = Validator::make($request->all(), [
            'kode_kategori_kegiatan' => 'required|string|max:20|unique:jenis_kegiatan,kode_kategori_kegiatan,' . $id_kategori_kegiatan,
            'nama_kategori_kegiatan' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $jenisKegiatan->update($request->only(['kode_kategori_kegiatan', 'nama_kategori_kegiatan']));

        return response()->json(['status' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    // Hapus data
    public function delete($id_kategori_kegiatan) {
        $jenisKegiatan = JenisKegiatanModel::find($id_kategori_kegiatan);

        if (!$jenisKegiatan) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        $jenisKegiatan->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }

    // Detail data
    public function show(string $id)
    {
        $jenisKegiatan = JenisKegiatanModel::find($id);
    
        // Jika data tidak ditemukan, kembalikan respons error
        if (!$jenisKegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }
    
        // Jika data ditemukan, tampilkan view
        return view('jenis_kegiatan.show', ['jenisKegiatan' => $jenisKegiatan]);
    }
}
