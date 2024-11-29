<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaModel;
use App\Models\JenisPenggunaModel;
use App\Models\KegiatanModel;
use App\Models\JabatanKegiatanModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class AgendaKegiatanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Agenda',
            'list' => ['Home', 'Agenda']
        ];

        $page = (object) [
            'title' => 'Daftar agenda yang ada'
        ];

        $activeMenu = 'agenda';

        return view('agenda.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
{
    $agenda = AgendaModel::with(['kegiatan', 'jenisPengguna', 'jabatanKegiatan'])
        ->select(
            'id_agenda',
            'kode_agenda',
            'nama_agenda',
            'id_kegiatan',
            'tempat_agenda',
            'id_jenis_pengguna',
            'id_jabatan_kegiatan',
            'bobot_anggota',
            'deskripsi',
            'tanggal_agenda'
        );

    return DataTables::of($agenda)
        ->addIndexColumn()
        ->addColumn('aksi', function ($agenda) {
            $btn = '<button onclick="modalAction(\''.url('/agenda/' . $agenda->id_agenda . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/agenda/' . $agenda->id_agenda . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/agenda/' . $agenda->id_agenda . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}
    public function create()
    {
        $jenisPengguna = JenisPenggunaModel::all(); // Ambil data jenis pengguna
        $kegiatan = KegiatanModel::all(); // Ambil data kegiatan
        $jabatanKegiatan = JabatanKegiatanModel::all(); // Ambil data jabatan kegiatan

        return view('agenda.create', compact('jenisPengguna', 'kegiatan', 'jabatanKegiatan'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_agenda' => 'required|unique:t_agenda,kode_agenda',
            'nama_agenda' => 'required|string',
            'id_kegiatan' => 'required|exists:m_kegiatan,id_kegiatan',
            'tempat_agenda' => 'required|string',
            'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
            'id_jabatan_kegiatan' => 'nullable|exists:m_jabatan_kegiatan,id_jabatan_kegiatan',
            'bobot_anggota' => 'required|numeric',
            'tanggal_agenda' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);        
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ], 400);
        }
        
        try {
            $agenda = AgendaModel::create([
                'kode_agenda' => $request->kode_agenda,
                'nama_agenda' => $request->nama_agenda,
                'id_kegiatan' => $request->id_kegiatan,
                'tempat_agenda' => $request->tempat_agenda,
                'id_jenis_pengguna' => $request->id_jenis_pengguna,
                'id_jabatan_kegiatan' => $request->id_jabatan_kegiatan ?? null, // Handle nullable field
                'bobot_anggota' => $request->bobot_anggota,
                'tanggal_agenda' => $request->tanggal_agenda,
                'deskripsi' => $request->deskripsi
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Data agenda berhasil disimpan',
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