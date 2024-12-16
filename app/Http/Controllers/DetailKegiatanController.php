<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DetailKegiatanModel;
use App\Models\DetailAgendaModel;
use App\Models\KegiatanModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DetailKegiatanController extends Controller
{
    // Menampilkan halaman daftar detail kegiatan
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Detail Kegiatan',
            'list' => ['Home', 'detail_kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar Progres kegiatan yang ada'
        ];

        $activeMenu = 'detail_kegiatan';

        return view('detail_kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) {
        $detail_kegiatan = DetailKegiatanModel::with('kegiatan:id_kegiatan,nama_kegiatan')
            ->select('id_detail_kegiatan', 'id_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja')
            ->get();    
    
        return DataTables::of($detail_kegiatan)
            ->addIndexColumn()
            ->addColumn('kegiatan', function ($detail_kegiatan) {
                // Menampilkan nama_kegiatan yang terkait dengan id_kegiatan
                return $detail_kegiatan->kegiatan ? $detail_kegiatan->kegiatan->nama_kegiatan : 'Tidak ada';
            })
            ->addColumn('aksi', function ($detail_kegiatan) {
                $btn = '<div class="d-flex justify-content-center">';  // Center the buttons horizontally
                $btn .= '<button onclick="modalAction(\''.route('detail_kegiatan.show', ['id' => $detail_kegiatan->id_detail_kegiatan]).'\')" class="btn btn-info btn-sm" style="margin-right: 5px;">';
                $btn .= '<i class="fas fa-eye"></i></button>';
                $btn .= '<button onclick="modalAction(\''.route('detail_kegiatan.edit', ['id' => $detail_kegiatan->id_detail_kegiatan]).'\')" class="btn btn-warning btn-sm">';
                $btn .= '<i class="fas fa-edit"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function getAverageProgress(Request $request)
    {
        $averageProgress = 0;

        if ($request->has('id_kegiatan') && $request->id_kegiatan) {
            // Mengambil rata-rata progres dari t_detail_agenda berdasarkan id_kegiatan
            $averageProgress = DB::table('t_detail_agenda')
                ->where('id_kegiatan', $request->id_kegiatan)
                ->avg('progres_agenda');
        }

        return response()->json([
            'success' => true,
            'averageProgress' => $averageProgress
        ]);
    }

    public function create(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Progres Kegiatan',
            'list' => ['Home', 'Progres Kegiatan', 'Tambah Progres Kegiatan']
        ];

        $user = Auth::user();

        $kegiatan = KegiatanModel::whereHas('tKegiatanUsers', function ($query) use ($user) {
            $query->whereHas('jabatanKegiatan', function ($subQuery) use ($user) {
                $subQuery->where('id_pengguna', $user->id_pengguna)
                        ->where('is_pic', true);
            });
        })->select('id_kegiatan', 'nama_kegiatan')->get();

        return view('detail_kegiatan.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
            'keterangan' => 'required|string|max:100',
            'progres_kegiatan' => 'required|numeric|min:0|max:100',
            'beban_kerja' => 'required|in:Ringan,Sedang,Berat',
        ], [
            'id_kegiatan.required' => 'Kegiatan harus dipilih.',
            'id_kegiatan.exists' => 'Kegiatan tidak valid.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'keterangan.max' => 'Keterangan maksimal 100 karakter.',
            'progres_kegiatan.required' => 'Progres kegiatan harus diisi.',
            'progres_kegiatan.numeric' => 'Progres kegiatan harus berupa angka.',
            'progres_kegiatan.min' => 'Progres kegiatan minimal 0.',
            'progres_kegiatan.max' => 'Progres kegiatan maksimal 100.',
            'beban_kerja.required' => 'Beban kerja harus dipilih.',
            'beban_kerja.in' => 'Pilihan beban kerja tidak valid.',
        ]);

        try {
            // Simpan data ke database
            DetailKegiatanModel::create([
                'id_kegiatan' => $request->id_kegiatan,
                'keterangan' => $request->keterangan,
                'progres_kegiatan' => $request->progres_kegiatan,
                'beban_kerja' => $request->beban_kerja,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Progres berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan detail detail kegiatan
    public function show(string $id) {
        $detailKegiatan = DetailKegiatanModel::find($id);

        if (!$detailKegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('detail_kegiatan.show', ['detailKegiatan' => $detailKegiatan]);
    }    
    
    // Menampilkan form edit detail kegiatan
    public function edit($id)
    {
        $detailKegiatan = DetailKegiatanModel::findOrFail($id);
        $kegiatanList = KegiatanModel::all();
        $averageProgress = 0;
    if ($detailKegiatan->id_kegiatan) {
        $averageProgress = DB::table('t_detail_agenda')
                            ->where('id_kegiatan', $detailKegiatan->id_kegiatan)
                            ->avg('progres_agenda');
    }
        return view('detail_kegiatan.edit', compact('detailKegiatan', 'kegiatanList', 'averageProgress'));
    }

    // Menyimpan perubahan data detail kegiatan
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kegiatan' => 'nullable|integer|exists:t_kegiatan,id_kegiatan',
            'keterangan' => 'nullable|string|max:255',
            'progres_kegiatan' => 'nullable|numeric|min:0|max:100',
            'beban_kerja' => 'nullable|in:Ringan,Sedang,Berat',
        ]);

        $detail_kegiatan = DetailKegiatanModel::findOrFail($id);
        try {
            $detail_kegiatan->update([
                'id_kegiatan' => $request->id_kegiatan,
                'keterangan' => $request->keterangan,
                'progres_kegiatan' => $request->progres_kegiatan,
                'beban_kerja' => $request->beban_kerja,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Progres berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui detail kegiatan. ' . $e->getMessage(),
            ]);
        }
    }

    // Eksport data ke PDF
    public function export_pdf()
    {
        // Ambil data detail kegiatan
        $detail_kegiatan = DetailKegiatanModel::with('kegiatan:id_kegiatan,nama_kegiatan')
            ->select('id_detail_kegiatan', 'id_kegiatan', 'keterangan', 'progres_kegiatan', 'beban_kerja')
            ->get();

        // Load view untuk PDF
        $pdf = Pdf::loadView('detail_kegiatan.export_pdf', ['detail_kegiatan' => $detail_kegiatan]);

        // Set ukuran kertas dan orientasi
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);

        // Stream untuk mendownload file PDF
        return $pdf->stream('Data Detail Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}