<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KegiatanModel;
use App\Models\KategoriKegiatanModel;
use App\Models\PenggunaModel;
use App\Models\JabatanKegiatanModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;

class KegiatanController extends Controller
{
    // Menampilkan halaman daftar kegiatan
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Input Kegiatan',
            'list' => ['Home', 'Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar kegiatan yang ada'
        ];

        $activeMenu = 'kegiatan2';

        return view('kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan data kegiatan dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $user = Auth::user();

        $kegiatan = KegiatanModel::query();

        // Jika pengguna bukan admin (id_jenis_pengguna = 1) atau manager (id_jenis_pengguna = 2)
        if (!in_array($user->id_jenis_pengguna, [1, 2])) {
            // Batasi data kegiatan berdasarkan id_pengguna di tabel kegiatan_user
            $kegiatan->whereHas('kegiatanUsers', function ($query) use ($user) {
                $query->where('t_kegiatan_user.id_pengguna', $user->id_pengguna);
            });
        }

        if ($user->id_jenis_pengguna == 1 || $user->id_jenis_pengguna == 3) {
            $kegiatan->select(
                'id_kegiatan',
                'kode_kegiatan',
                'nama_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'periode',
                'id_kategori_kegiatan',
                'tempat_kegiatan'
            );
        } elseif ($user->id_jenis_pengguna == 2) {
            $kegiatan->select(
                'id_kegiatan',
                'nama_kegiatan'
            );
        }


        // Filter berdasarkan periode jika ada
        if ($request->has('periode_filter') && $request->periode_filter != '') {
            $kegiatan->where('periode', $request->periode_filter);
        }

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('kategori_kegiatan', function ($kegiatan) {
                return $kegiatan->kategoriKegiatan ? $kegiatan->kategoriKegiatan->nama_kategori_kegiatan : 'Tidak ada kategori';
            })
            ->addColumn('status', function ($kegiatan) {
                $status = 'Belum selesai';
                $statusClass = 'badge-warning';
                $progres = DB::table('t_detail_kegiatan')
                ->where('id_kegiatan', $kegiatan->id_kegiatan)
                ->orderByDesc('updated_at')
                ->value('progres_kegiatan');

                if ($progres !== null) {
                    if ($progres == 100) {
                        $status = 'Selesai';
                        $statusClass = 'badge-success';
                    } elseif ($progres < 100 && $kegiatan->tanggal_selesai < now()) {
                        $status = 'Tidak selesai';
                        $statusClass = 'badge-danger';
                    }
                }

                return '<span class="badge ' . $statusClass . '">' . $status . '</span>';
            })
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<div class="d-flex justify-content-center">';
        
                // Tombol Show ditampilkan untuk semua pengguna
                $btn .= '<button onclick="modalAction(\''.route('kegiatan.show', $kegiatan->id_kegiatan).'\')" class="btn btn-info btn-sm mr-2">';
                $btn .= '<i class="fas fa-eye"></i></button>';
        
                // Tombol Edit dan Delete hanya ditampilkan untuk pengguna dengan id_jenis_pengguna == 1
                if (Auth::user()->id_jenis_pengguna == 1) {
                    $btn .= '<button onclick="modalAction(\''.route('kegiatan.edit', $kegiatan->id_kegiatan).'\')" class="btn btn-warning btn-sm mr-2">';
                    $btn .= '<i class="fas fa-edit"></i></button>';
        
                    $btn .= '<button onclick="modalAction(\''.route('kegiatan.confirm', $kegiatan->id_kegiatan).'\')" class="btn btn-danger btn-sm mr-2">';
                    $btn .= '<i class="fas fa-trash"></i></button>';
                }
                    if (Auth::user()->id_jenis_pengguna == 1 || Auth::user()->id_jenis_pengguna == 2) {
                       // Tombol Unduh Surat Tugas
                       $btn .= '<a href="'.route('kegiatan.downloadDraft', $kegiatan->id_kegiatan).'" target="_blank" class="btn btn-primary btn-sm ml-2">';
                       $btn .= '<i class="fas fa-download"></i></a>';                
                    }
                    if (Auth::user()->id_jenis_pengguna == 1) {
                        $btn .= '<button onclick="modalAction(\''.route('kegiatan.uploadForm', $kegiatan->id_kegiatan).'\')" class="btn btn-success btn-sm ml-2">';
                        $btn .= '<i class="fas fa-upload"></i></button>';
                        return $btn . '</div>';
                    }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    // Menampilkan form tambah kegiatan via Ajax
    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah Kegiatan',
            'list' => ['Home', 'Kegiatan', 'Tambah Kegiatan']
        ];

        // Mengambil data kategori kegiatan
        $kategoriKegiatan = KategoriKegiatanModel::all();

        // Mengambil pengguna dengan id_jenis_pengguna = 3
        $pengguna = PenggunaModel::where('id_jenis_pengguna', 3)->get();


        // Mengambil data jabatan kegiatan
        $jabatanKegiatan = JabatanKegiatanModel::all();
    
        return view('kegiatan.create', ['breadcrumb' => $breadcrumb, 'kategoriKegiatan' => $kategoriKegiatan, 'pengguna' => $pengguna,'jabatanKegiatan' => $jabatanKegiatan]);
    }

    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'kode_kegiatan' => 'required|string|max:10|unique:t_kegiatan,kode_kegiatan',
            'nama_kegiatan' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'periode' => 'required|string|max:50',
            'tempat_kegiatan' => 'required|string|max:255',
            'id_kategori_kegiatan' => 'required|exists:m_kategori_kegiatan,id_kategori_kegiatan',
            'anggota.*.id_pengguna' => 'required|exists:m_pengguna,id_pengguna',
            'anggota.*.id_jabatan_kegiatan' => 'required|exists:m_jabatan_kegiatan,id_jabatan_kegiatan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Menyimpan data kegiatan ke tabel t_kegiatan
        $kegiatan = KegiatanModel::create($request->only(
            'kode_kegiatan',
            'nama_kegiatan',
            'tanggal_mulai',
            'tanggal_selesai',
            'periode',
            'id_kategori_kegiatan',
            'tempat_kegiatan'
        ));

        // Menyimpan anggota ke tabel pivot t_kegiatan_user
        if ($request->has('anggota') && is_array($request->anggota)) {
            foreach ($request->anggota as $anggota) {
                DB::table('t_kegiatan_user')->insert([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_pengguna' => $anggota['id_pengguna'],
                    'id_jabatan_kegiatan' => $anggota['id_jabatan_kegiatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Data kegiatan berhasil disimpan',
        ]);
    }

    public function show(string $id)
    {
        $kegiatan = KegiatanModel::with(['anggota', 'agenda.detailAgenda', 'detailKegiatan'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Kegiatan',
            'list' => ['Home', 'Kegiatan']
        ];
        
        return view('kegiatan.show', [
            'kegiatan' => $kegiatan,
            'breadcrumb' => $breadcrumb
        ]);
    }


    // Menampilkan form edit kegiatan via Ajax
    public function edit($id) {
        // Mengambil data kegiatan
        $kegiatan = KegiatanModel::findOrFail($id);
        
        // Mengambil kategori kegiatan
        $kategoriKegiatan = KategoriKegiatanModel::all();
        
        // Mengambil pengguna dengan id_jenis_pengguna = 3
        $pengguna = PenggunaModel::where('id_jenis_pengguna', 3)->get();
        
        // Mengambil data jabatan kegiatan
        $jabatanKegiatan = JabatanKegiatanModel::all();
        
        // Menambahkan data pivot 'id_jabatan_kegiatan' ke pengguna yang terhubung dengan kegiatan
        $kegiatan->load('pengguna'); // Menyertakan relasi pengguna dengan pivot data

        // Kirim data ke view
        return view('kegiatan.edit', [
            'kegiatan' => $kegiatan, 
            'kategoriKegiatan' => $kategoriKegiatan, 
            'pengguna' => $pengguna, 
            'jabatanKegiatan' => $jabatanKegiatan
        ]);
    }


    public function update(Request $request, $id)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'kode_kegiatan' => 'nullable|string|max:10|unique:t_kegiatan,kode_kegiatan,' . $id . ',id_kegiatan',
            'nama_kegiatan' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'periode' => 'nullable|string|max:50',
            'tempat_kegiatan' => 'required|string|max:255',
            'id_kategori_kegiatan' => 'nullable|exists:m_kategori_kegiatan,id_kategori_kegiatan',
            'anggota.*.id_pengguna' => 'nullable|exists:m_pengguna,id_pengguna',
            'anggota.*.id_jabatan_kegiatan' => 'nullable|exists:m_jabatan_kegiatan,id_jabatan_kegiatan',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors(),
            ]);
        }
    
        // Temukan kegiatan berdasarkan ID
        $kegiatan = KegiatanModel::findOrFail($id);
    
        $kegiatan->kode_kegiatan = $request->kode_kegiatan ?? $kegiatan->kode_kegiatan;
        $kegiatan->nama_kegiatan = $request->nama_kegiatan ?? $kegiatan->nama_kegiatan;
        $kegiatan->tanggal_mulai = $request->tanggal_mulai ?? $kegiatan->tanggal_mulai;
        $kegiatan->tanggal_selesai = $request->tanggal_selesai ?? $kegiatan->tanggal_selesai;
        $kegiatan->periode = $request->periode ?? $kegiatan->periode;
        $kegiatan->id_kategori_kegiatan = $request->id_kategori_kegiatan ?? $kegiatan->id_kategori_kegiatan;
        $kegiatan->tempat_kegiatan = $request->tempat_kegiatan ?? $kegiatan->tempat_kegiatan;
    
        // Simpan perubahan
        $kegiatan->save();

        // Menyimpan anggota ke tabel pivot t_kegiatan_user jika ada
        if ($request->has('anggota') && is_array($request->anggota)) {
            // Hapus anggota yang lama dari pivot sebelum menambahkan yang baru
            DB::table('t_kegiatan_user')->where('id_kegiatan', $kegiatan->id_kegiatan)->delete();

            // Menambahkan anggota yang baru ke tabel pivot
            foreach ($request->anggota as $anggota) {
                if (isset($anggota['id_pengguna']) && isset($anggota['id_jabatan_kegiatan'])) {
                    DB::table('t_kegiatan_user')->insert([
                        'id_kegiatan' => $kegiatan->id_kegiatan,
                        'id_pengguna' => $anggota['id_pengguna'],
                        'id_jabatan_kegiatan' => $anggota['id_jabatan_kegiatan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui',
        ]);
    }    

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kegiatan = KegiatanModel::with('kategoriKegiatan', 'users')->find($id); // Pastikan relasi 'users' sudah didefinisikan
            
            if ($kegiatan) {
                $kegiatan->users()->detach(); // Menghapus data di tabel pivot jika relasi menggunakan belongsToMany
                $kegiatan->delete(); // Hapus data utama
                
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
        $kegiatan = KegiatanModel::with('kategoriKegiatan')->find($id);
    
        // Jika data level tidak ditemukan, kirimkan respon error
        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ]);
        }
    
        // Kembalikan view konfirmasi penghapusan level
        return view('kegiatan.confirm', ['kegiatan' => $kegiatan]);
    }

    public function export_pdf()
    {
        // Mengambil data kegiatan beserta kategori_kegiatan
        $kegiatan = KegiatanModel::select('kode_kegiatan', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'periode', 'id_kategori_kegiatan', 'tempat_kegiatan')
            ->with('kategoriKegiatan')
            ->orderBy('kode_kegiatan')
            ->get();

        // Load view untuk PDF
        $pdf = Pdf::loadView('kegiatan.export_pdf', ['kegiatan' => $kegiatan]);

        // Set ukuran kertas dan orientasi
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true); // Jika ada gambar yang diambil dari URL

        // Stream untuk mendownload file PDF
        return $pdf->stream('Data Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function unduhSuratTugas($id_kegiatan)
    {
        try {
            // Ambil data kegiatan beserta panitia dari database menggunakan relasi Eloquent
            $kegiatan = KegiatanModel::with('pengguna')->findOrFail($id_kegiatan);
    
            // Buat instance Dompdf
            $dompdf = new Dompdf();
    
            // Load view untuk membuat PDF
            $pdfView = view('kegiatan.surat_tugas', compact('kegiatan'))->render();
            $dompdf->loadHtml($pdfView);
    
            $dompdf->setPaper('A4', 'P');
            $dompdf->render();
            $dompdf->stream("surat_tugas_{$kegiatan->nama_kegiatan}.pdf", ["Attachment" => true]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
      }
    }

    public function showUploadForm($id_kegiatan)
    {
        $kegiatan = KegiatanModel::findOrFail($id_kegiatan);

        return view('kegiatan.upload', [
            'id_kegiatan' => $id_kegiatan,
            'kegiatan' => $kegiatan,
        ]);
    }
    public function upload(Request $request, $id_kegiatan)
    {
        $request->validate([
            'file_surat_tugas' => 'required|mimes:pdf,doc,docx|max:2048', // Validasi file
        ]);

        $kegiatan = KegiatanModel::findOrFail($id_kegiatan);

        try {
            // Simpan file ke folder 'uploads/surat_tugas'
            $file = $request->file('file_surat_tugas');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/surat_tugas'), $filename);

            // Simpan nama file ke database
            $kegiatan->file_surat_tugas = 'uploads/surat_tugas/' . $filename;
            $kegiatan->save();

            return response()->json([
                'status' => true,
                'message' => 'Surat tugas berhasil diunggah.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengunggah file.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function downloadSuratTugas($id_kegiatan)
    {
        $kegiatan = KegiatanModel::findOrFail($id_kegiatan);

        if ($kegiatan && $kegiatan->file_surat_tugas) {
            $filePath = storage_path('app/public/' . $kegiatan->file_surat_tugas);

            if (file_exists($filePath)) {
                return response()->download($filePath);
            }
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function listDosen(Request $request)
{
    $kegiatan = KegiatanModel::query()
        ->with('kategoriKegiatan') // Relasi kategori kegiatan
        ->select(
            'id_kegiatan',
            'kode_kegiatan',
            'nama_kegiatan',
            'tanggal_mulai',
            'tanggal_selesai',
            'periode',
            'id_kategori_kegiatan',
            'tempat_kegiatan'
        );

    // Filter berdasarkan periode jika ada
    if ($request->has('periode_filter') && $request->periode_filter != '') {
        $kegiatan->where('periode', $request->periode_filter);
    }

    return DataTables::of($kegiatan)
        ->addIndexColumn()
        ->addColumn('kategori_kegiatan', function ($kegiatan) {
            return $kegiatan->kategoriKegiatan ? $kegiatan->kategoriKegiatan->nama_kategori_kegiatan : 'Tidak ada kategori';
        })
        ->addColumn('status', function ($kegiatan) {
            $status = 'Belum selesai';
            $statusClass = 'badge-warning';
            $progres = DB::table('t_detail_kegiatan')
                ->where('id_kegiatan', $kegiatan->id_kegiatan)
                ->orderByDesc('updated_at')
                ->value('progres_kegiatan');

            if ($progres !== null) {
                if ($progres == 100) {
                    $status = 'Selesai';
                    $statusClass = 'badge-success';
                } elseif ($progres < 100 && $kegiatan->tanggal_selesai < now()) {
                    $status = 'Tidak selesai';
                    $statusClass = 'badge-danger';
                }
            }

            return '<span class="badge ' . $statusClass . '">' . $status . '</span>';
        })
        ->make(true);
}

}