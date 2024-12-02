<?php

namespace App\Http\Controllers;

use App\Models\DraftSuratTugas;
use App\Models\KegiatanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DraftSuratTugasController extends Controller
{
    public function index()
    {
        // Breadcrumb data
        $breadcrumb = (object) [
            'title' => 'Daftar Draft Surat Tugas',
            'list' => ['Home', 'Draft Surat Tugas']
        ];
    
        // Informasi halaman
        $page = (object) [
            'title' => 'Daftar draft surat tugas yang tersedia'
        ];
    
        // Menu aktif
        $activeMenu = 'draft_surat_tugas';

        $kegiatan = KegiatanModel::all();
        
        // Kirim data ke view
        return view('draft_surat_tugas.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kegiatan' => $kegiatan,
            'activeMenu' => $activeMenu
        ]);
    }    
    public function list(Request $request){
    $draftSuratTugas = DraftSuratTugas::with(['kegiatan'])
    ->select(
        'id_draft',
        'kode_surat',
        'judul_surat',
        'id_kegiatan',
        'created_at'
    );
    
    return DataTables::of($draftSuratTugas)
        ->addIndexColumn()
        ->addColumn('aksi', function ($draft) {
            $btn = '<button onclick="exportPdf(' . $draft->id_draft . ')" class="btn btn-primary btn-sm">Eksport PDF</button>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}

    public function create()
    {
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan')->get();
        return view('draft_surat_tugas.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_surat' => 'required|string|max:50',
            'judul_surat' => 'required|string|max:100',
            'id_kegiatan' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }
        // Simpan data ke database
        DraftSuratTugas::create([
            'kode_surat' => $request->kode_surat,
            'judul_surat' => $request->judul_surat,
            'id_kegiatan' => $request->id_kegiatan
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data Agenda berhasil disimpan',
        ]);
    }
}
