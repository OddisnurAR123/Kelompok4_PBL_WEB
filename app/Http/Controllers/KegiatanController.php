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

        $activeMenu = 'kegiatan';

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
                'id_kategori_kegiatan'
            );

        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('kategori_kegiatan', function ($kegiatan) {
                return $kegiatan->kategoriKegiatan ? $kegiatan->kategoriKegiatan->nama_kategori_kegiatan : 'Tidak ada kategori';
            })    
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<button onclick="modalAction(\''.route('kegiatan.show', $kegiatan->id_kegiatan).'\')" class="btn btn-info btn-sm">';
                $btn .= '<i class="fas fa-eye"></i> Detail</button>';
                $btn .= '<button onclick="modalAction(\''.route('kegiatan.edit', $kegiatan->id_kegiatan).'\')" class="btn btn-warning btn-sm">';
                $btn .= '<i class="fas fa-edit"></i> Edit</button>'; 
                $btn .= '<button onclick="modalAction(\''.route('kegiatan.delete', $kegiatan->id_kegiatan).'\')" class="btn btn-danger btn-sm">';
                $btn .= '<i class="fas fa-trash"></i> Hapus</button>';              
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
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'kode_kegiatan' => 'required|string|max:10|unique:t_kegiatan,kode_kegiatan',
                'nama_kegiatan' => 'required|string|max:100',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
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

            try {
                // Mulai transaksi
                DB::beginTransaction();

                // Menyimpan data kegiatan ke tabel t_kegiatan
                $kegiatan = KegiatanModel::create($request->only(
                    'kode_kegiatan',
                    'nama_kegiatan',
                    'tanggal_mulai',
                    'tanggal_selesai',
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

                // Commit transaksi
                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data kegiatan berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                // Rollback jika terjadi kesalahan
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Request bukan AJAX.',
        ]);
    }

    public function show(string $id)
    {
        $kegiatan = KegiatanModel::with(['anggota'])->find($id);

        // Debugging
        if (!$kegiatan) {
            dd("Kegiatan dengan ID $id tidak ditemukan");
        }

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
        $kegiatan = KegiatanModel::findOrFail($id);
        $kategoriKegiatan = KategoriKegiatanModel::all();
        // Mengambil pengguna dengan id_jenis_pengguna = 3
        $pengguna = PenggunaModel::where('id_jenis_pengguna', 3)->get();
        // Mengambil data jabatan kegiatan
        $jabatanKegiatan = JabatanKegiatanModel::all();
        return view('kegiatan.edit', ['kegiatan' => $kegiatan, 'kategoriKegiatan' => $kategoriKegiatan, 'pengguna' => $pengguna, 'jabatanKegiatan' => $jabatanKegiatan]);
    }

    public function update(Request $request, $id)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'kode_kegiatan' => 'nullable|string|max:10|unique:t_kegiatan,kode_kegiatan,' . $id . ',id_kegiatan',
            'nama_kegiatan' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
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
            $kegiatan = KegiatanModel::find($id);

            if ($kegiatan) {
                $kegiatan->delete();
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
                // validasi file harus xls atau xlsx, max 1MB 
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
 
            $file = $request->file('file_kegiatan');  // ambil file dari request 
 
            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                        $insert[] = [ 
                            'kode_kegiatan' => $value['A'], 
                            'nama_kegiatan' => $value['B'], 
                            'tanggal_mulai' => $value['C'], 
                            'tanggal_selesai' => $value['D'], 
                            'id_kategori_kegiatan' => $value['E'], 
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    KegiatanModel::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            }else{ 
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
        $sheet->setCellValue('F1', 'Kategori Kegiatan');

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
            $sheet->setCellValue('F' . $baris, $value->kategoriKegiatan->nama_kategori_kegiatan ?? 'Tidak Ada Kategori'); // Ambil nama kategori
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
    $kegiatan = KegiatanModel::select('kode_kegiatan', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'id_kategori_kegiatan')
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