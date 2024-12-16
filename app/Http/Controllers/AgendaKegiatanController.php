<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanUser;
use App\Models\PenggunaModel;
use App\Models\DetailAgendaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;




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
        // Mengambil data agenda dengan relasi kegiatan dan kegiatanUser
        $agenda = AgendaModel::with(['kegiatan', 'kegiatanUser', 'pengguna'])
            ->select(
                'id_agenda',
                'nama_agenda',
                'id_kegiatan',
                'tempat_agenda',
                'id_pengguna',
                'bobot_anggota',
                'deskripsi',
                'tanggal_agenda'
            );

        // Mengembalikan data untuk DataTables
        return DataTables::of($agenda)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($agenda) {
                // Menambahkan tombol aksi untuk setiap baris
                $btn = '<button onclick="modalAction(\''.url('/agenda/' . $agenda->id_agenda . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/agenda/' . $agenda->id_agenda . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/agenda/' . $agenda->id_agenda . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Menandai kolom aksi sebagai HTML
            ->make(true); // Menghasilkan output JSON untuk DataTables
    }
    public function create(Request $request)
{
    try {
        $id_kegiatan = $request->input('id_kegiatan', 1);  

        // Mengambil data kegiatan berdasarkan ID
        $kegiatan = KegiatanModel::findOrFail($id_kegiatan);

        // Mengambil semua pengguna yang terkait dengan kegiatan ini
        $pengguna = KegiatanUser::with('pengguna:id_pengguna,nama_pengguna')
                            ->where('id_kegiatan', $id_kegiatan)
                            ->whereHas('pengguna', function ($query) {
                                $query->where('id_jabatan_kegiatan', '!=', 1);
                            })
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'id_pengguna' => $item->id_pengguna,
                                    'nama_pengguna' => $item->pengguna ? $item->pengguna->nama_pengguna : 'N/A',
                                ];
                            });

        return view('agenda.create', compact('kegiatan', 'pengguna'));
    } catch (\Exception $e) {
        return response()->json(['message' => 'Kegiatan tidak ditemukan.', 'error' => $e->getMessage()], 500);
    }
}
public function getPengguna(Request $request)
{
    $id_kegiatan = $request->input('id_kegiatan');

    if ($id_kegiatan) {
        try {
            $kegiatan = KegiatanModel::findOrFail($id_kegiatan);

            if ($kegiatan->id_jabatan_kegiatan == 1) {
                return response()->json([]);  // Mengembalikan array kosong jika ID jabatan adalah 1
            }

            $pengguna = KegiatanUser::with('pengguna:id_pengguna,nama_pengguna')
                            ->where('id_kegiatan', $id_kegiatan)
                            ->whereHas('pengguna', function ($query) {
                                $query->where('id_jabatan_kegiatan', '!=', 1);
                            })
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'id_pengguna' => $item->id_pengguna,
                                    'nama_pengguna' => $item->pengguna ? $item->pengguna->nama_pengguna : 'N/A',
                                ];
                            });

            return response()->json($pengguna);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Data pengguna tidak ditemukan.', 'error' => $e->getMessage()], 500);
        }
    }

    return response()->json([]);
}

// Method untuk menyimpan data agenda ke database
public function store(Request $request)
{
    $request->validate([
        'nama_agenda' => 'required|string|max:255',
        'id_pengguna' => 'required|integer',
        'bobot_anggota' => 'required|numeric',
        'tempat_agenda' => 'required|string|max:255',
        'tanggal_agenda' => 'required|date',
        'deskripsi' => 'nullable|string',
    ]);

    try {
        // Simpan data ke database
        AgendaModel::create($request->all());

        return response()->json([
            'message' => 'Data agenda berhasil disimpan.',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan saat menyimpan data.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


        public function edit(string $id_agenda)
{
    $agenda = AgendaModel::findOrFail($id_agenda);

    // Mengambil semua kegiatan dari tabel t_kegiatan
    $kegiatan = KegiatanModel::all();

    // Mengambil pengguna yang terkait dengan kegiatan yang dipilih
    $kegiatanUser = KegiatanUser::with('pengguna:id_pengguna,nama_pengguna')
        ->where('id_kegiatan', $agenda->id_kegiatan)
        ->whereHas('pengguna', function ($query) {
            $query->where('id_jabatan_kegiatan', '!=', 1);
        })
        ->get();

    return view('agenda.edit', compact('agenda', 'kegiatan', 'kegiatanUser'));
}

public function update(Request $request, $id_agenda)
{
    $agenda = AgendaModel::findOrFail($id_agenda);

    $rules = [
        'nama_agenda' => 'required|string|max:255',
        'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
        'tempat_agenda' => 'required|string|max:255',
        'id_pengguna' => 'nullable|exists:t_kegiatan_user,id_pengguna',
        'bobot_anggota' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string',
        'tanggal_agenda' => 'required|date|date_format:Y-m-d\TH:i',
    ];
    
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal.',
            'msgField' => $validator->errors(),
        ]);
    }    
    $agenda->update($request->all()); // bobot_anggota secara otomatis akan diperbarui jika ada di request.

    return response()->json([
        'status' => true,
        'message' => 'Data agenda berhasil diperbarui',
    ]);
}

public function show(string $id)
{
    $agenda = AgendaModel::with(['kegiatan', 'kegiatanUser.pengguna.jabatanKegiatan'])->find($id);

    // Jika data tidak ditemukan, kembalikan respons JSON error
    if (!$agenda) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }

    // Ambil detail agenda terkait
    $detailAgenda = DetailAgendaModel::with(['kegiatan', 'agenda'])->where('id_agenda', $id)->first();

    // Tampilkan view dengan data agenda dan detail agenda
    return view('agenda.show', [
        'agenda' => $agenda,
        'detailAgenda' => $detailAgenda
    ]);
}

public function delete(Request $request, $id)
{
    Log::info("Delete Request ID: {$id}, Method: {$request->method()}");

    // Cari agenda berdasarkan ID
    $agenda = AgendaModel::find($id);

    if (!$agenda) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan',
        ]);
    }

    // Pastikan hanya request AJAX yang memproses penghapusan
    if ($request->ajax() || $request->wantsJson()) {
        $agenda->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }

    return redirect()->back()->with('error', 'Permintaan penghapusan hanya melalui AJAX.');
}

public function confirm(string $id)
{
    $agenda = AgendaModel::with(['pengguna'])->find($id);

    if (!$agenda) {
        return redirect()->back()->with('error', 'Agenda tidak ditemukan.');
    }

    return view('agenda.confirm', compact('agenda'));
}
}