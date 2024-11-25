<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KegiatanModel;
use App\Models\KategoriKegiatanModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Barryvdh\DomPDF\Facade\Pdf;

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
                $btn = '<button onclick="modalAction(\''.route('kegiatan.show', $kegiatan->id_kegiatan).'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.route('kegiatan.edit', $kegiatan->id_kegiatan).'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.route('kegiatan.delete', $kegiatan->id_kegiatan).'\')" class="btn btn-danger btn-sm">Hapus</button>';
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

        $kategoriKegiatan = KategoriKegiatanModel::all();
    
        return view('kegiatan.create', ['breadcrumb' => $breadcrumb, 'kategoriKegiatan' => $kategoriKegiatan]);
    }

    public function store(Request $request)
    {
        dd($request->all());
        if ($request->ajax() || $request->wantsJson()) { 
            $validator = Validator::make($request->all(), [
                'kode_kegiatan' => 'required|string|max:10|unique:t_kegiatan,kode_kegiatan',
                'nama_kegiatan' => 'required|string|max:100',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'id_kategori_kegiatan' => 'required|exists:m_kategori_kegiatan,id_kategori_kegiatan',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            // Menyimpan data kegiatan ke database
            KegiatanModel::create($request->only('kode_kegiatan', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'id_kategori_kegiatan'));
    
            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan berhasil disimpan',
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Request bukan AJAX.',
        ]);
    }    

    public function show(string $id)
    {
        $kegiatan = KegiatanModel::with('kategoriKegiatan')->find($id);

        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ]);
        }

        $breadcrumb = (object) [
            'title' => 'Input Kegiatan',
            'list' => ['Home', 'Kegiatan']
        ];

        return view('kegiatan.show', ['kegiatan' => $kegiatan, 'breadcrumb' => $breadcrumb]);
    }

    // Menampilkan form edit kegiatan via Ajax
    public function edit($id) {
        $kegiatan = KegiatanModel::findOrFail($id);
        $kategoriKegiatan = KategoriKegiatanModel::all();
        return view('kegiatan.edit', ['kegiatan' => $kegiatan, 'kategoriKegiatan' => $kategoriKegiatan]);
    }

    // Menyimpan perubahan data kegiatan via Ajax
    public function update(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'kode_kegiatan' => 'required|string|max:10|unique:t_kegiatan,kode_kegiatan',
                'nama_kegiatan' => 'required|string|max:100',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'id_kategori_kegiatan' => 'required|exists:m_kategori_kegiatan,id_kategori_kegiatan',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $kegiatan = KegiatanModel::findOrFail($id);
            $kegiatan->update($request->only('kode_kegiatan', 'nama_kegiatan','tanggal_mulai','tanggal_selesai','id_kategori_kegiatan'));

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Request bukan AJAX.',
        ]);
    }

    // Menampilkan konfirmasi hapus kegiatan via Ajax
    public function confirm($id) {
        $kegiatan = KegiatanModel::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Apakah Anda yakin ingin menghapus kegiatan ini?',
            'data' => $kegiatan,
        ]);
    }

    // Menghapus data kegiatan via Ajax
    public function delete(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $kegiatan = KegiatanModel::findOrFail($id);
            $kegiatan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Request bukan AJAX.',
        ]);
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
        ->orderBy('id_kategori_kegiatan')
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