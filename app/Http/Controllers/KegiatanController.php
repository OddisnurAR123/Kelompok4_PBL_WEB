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
    public function list(Request $request) {
        $kegiatan = KegiatanModel::with('kategoriKegiatan') // Relasi kategori kegiatan
            ->select(
                'id_kegiatan',
                'kode_kegiatan',
                'nama_kegiatan',
                'tanggal_mulai',
                'tanggal_selesai',
                'periode',
                'id_kategori_kegiatan'
            );

            return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('kategori_kegiatan', function ($kegiatan) {
                return $kegiatan->kategoriKegiatan ? $kegiatan->kategoriKegiatan->nama_kategori_kegiatan : 'Tidak ada kategori';
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
        
                    $btn .= '<button onclick="modalAction(\''.route('kegiatan.delete', $kegiatan->id_kegiatan).'\')" class="btn btn-danger btn-sm mr-2">';
                    $btn .= '<i class="fas fa-trash"></i></button>';
                }
        
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
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
            'id_kategori_kegiatan'
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
        // Pastikan Anda sudah menambahkan relasi 'pengguna' di model Kegiatan
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
            'message' => 'Data berhasil diupdate',
        ]);
    }    

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kegiatan = KegiatanModel::with('kategoriKegiatan', 'users')->find($id); // Pastikan relasi 'users' sudah didefinisikan
            
            if ($kegiatan) {
                $kegiatan->users()->detach(); // Menghapus data di tabel pivot jika relasi menggunakan `belongsToMany`
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

    public function import() 
    { 
        return view('kegiatan.import'); 
    }

    // Proses import excel kegiatan dengan AJAX
    public function import_ajax(Request $request) {
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // Validasi file harus xls atau xlsx, max 1MB 
                'file_kegiatan' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
    
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
    
            $file = $request->file('file_kegiatan');  // Ambil file dari request 
    
            $reader = IOFactory::createReader('Xlsx');  // Load reader file excel 
            $reader->setReadDataOnly(true);             // Hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // Load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // Ambil sheet yang aktif 
    
            $data = $sheet->toArray(null, false, true, true);   // Ambil data excel 
    
            $insert = []; 
            if(count($data) > 1){ // Jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // Baris ke 1 adalah header, maka lewati 
                        // Konversi tanggal mulai dan selesai dari format Excel ke format MySQL
                        $tanggal_mulai = Date::excelToDateTimeObject($value['C'])->format('Y-m-d H:i:s');
                        $tanggal_selesai = Date::excelToDateTimeObject($value['D'])->format('Y-m-d H:i:s');
    
                        $insert[] = [ 
                            'kode_kegiatan' => $value['A'], 
                            'nama_kegiatan' => $value['B'], 
                            'tanggal_mulai' => $tanggal_mulai, 
                            'tanggal_selesai' => $tanggal_selesai, 
                            'periode' => $value['E'], 
                            'id_kategori_kegiatan' => $value['F'], 
                        ]; 
                    } 
                } 
    
                if(count($insert) > 0){ 
                    // Insert data ke database
                    KegiatanModel::insert($insert);    
                } 
    
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            } else { 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/');
    }

    public function export_excel()
    {
        // Ambil data kegiatan yang akan diexport
        $kegiatan = KegiatanModel::select(
            'kode_kegiatan',
            'nama_kegiatan',
            'tanggal_mulai',
            'tanggal_selesai',
            'periode', 
            'id_kategori_kegiatan'
        )
        ->with('kategoriKegiatan') // Relasi dengan kategori
        ->get();

        // Load library PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kegiatan');
        $sheet->setCellValue('C1', 'Nama Kegiatan');
        $sheet->setCellValue('D1', 'Tanggal Mulai');
        $sheet->setCellValue('E1', 'Tanggal Selesai');
        $sheet->setCellValue('F1', 'Periode');
        $sheet->setCellValue('G1', 'Kategori Kegiatan');

        // Buat header bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Isi data kegiatan
        $no = 1; // Nomor urut
        $baris = 2; // Baris dimulai dari baris ke-2
        foreach ($kegiatan as $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kode_kegiatan);
            $sheet->setCellValue('C' . $baris, $value->nama_kegiatan);
            $sheet->setCellValue('D' . $baris, $value->tanggal_mulai);
            $sheet->setCellValue('E' . $baris, $value->tanggal_selesai);
            $sheet->setCellValue('F' . $baris, $value->periode);
            $sheet->setCellValue('G' . $baris, $value->kategoriKegiatan->nama_kategori_kegiatan ?? 'Tidak Ada Kategori'); // Ambil nama kategori
            $baris++;
            $no++;
        }

        // Set auto width untuk kolom
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set judul sheet
        $sheet->setTitle('Data Kegiatan');

        // Buat file Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kegiatan ' . date('Y-m-d H:i:s') . '.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // Simpan output ke browser
        $writer->save('php://output');
        exit;
    }


    public function export_pdf()
    {
        // Mengambil data kegiatan beserta kategori_kegiatan
        $kegiatan = KegiatanModel::select('kode_kegiatan', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'periode', 'id_kategori_kegiatan')
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
}