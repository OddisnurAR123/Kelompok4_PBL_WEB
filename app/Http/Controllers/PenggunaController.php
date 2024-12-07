<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use App\Models\JenisPenggunaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;


class PenggunaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Dashboard', 'Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar pengguna yang ada'
        ];

        $activeMenu = 'pengguna';

        $jenisPengguna = JenisPenggunaModel::all();

        return view('pengguna.index',[
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenisPengguna' => $jenisPengguna,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $pengguna = PenggunaModel::select(
                'id_pengguna',
                'id_jenis_pengguna', 
                'nama_pengguna',
                'username', 
                'password', 
                'nip',
                'email', 
            )->with('jenisPengguna');
            if ($request->id_jenis_pengguna) {
                $pengguna->where('id_jenis_pengguna', $request->id_jenis_pengguna);
            }
        return DataTables::of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                $btn = '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/delete').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['foto', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();// Ambil data jenis pengguna dari database
        return view('pengguna.create')->with('jenisPengguna',$jenisPengguna);
    }    

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk input 
            $rules = [
                'id_jenis_pengguna' => 'required|integer',
                'nama_pengguna' => 'required|string|max:100',
                'username' => 'required|string|unique:m_pengguna,username|max:50',
                'password' => 'required|string|min:6',
                'nip' => 'required|string|max:100',
                'email' => 'nullable|email|max:100',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // Mengembalikan respon jika validasi gagal
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            PenggunaModel::create([
                'id_jenis_pengguna' => $request->id_jenis_pengguna,
                'nama_pengguna' => $request->nama_pengguna,
                'username' => $request->username,
                'password' => bcrypt($request->password), // Hashing password
                'nip' => $request->nip,
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pengguna berhasil ditambahkan.',
            ]);
        }

        return redirect('/');
    }

        public function edit(string $id_pengguna) {
            $pengguna = PenggunaModel::find($id_pengguna);
            $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();

            return view('pengguna.edit', ['pengguna' => $pengguna, 'jenisPengguna' => $jenisPengguna]);
        }

        public function update(Request $request, $id_pengguna) {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
                    'nama_pengguna' => 'required|string',
                    'username' => 'required|string|unique:m_pengguna,username,' . $id_pengguna . ',id_pengguna',
                    'password' => 'required|string|min:6',
                    'nip' => 'required|string',
                    'email' => 'required|email|unique:m_pengguna,email,' . $id_pengguna . ',id_pengguna',
                ];
        
                $validator = Validator::make($request->all(), $rules);
        
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal.',
                        'msgField' => $validator->errors(),
                    ]);
                }
                    // Update pengguna with new data
                    $pengguna = PenggunaModel::find($id_pengguna);                    
                    if ($pengguna) {
                        $pengguna->update($request->all());
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

            public function show(string $id)
            {
                $pengguna = PenggunaModel::find($id);
            
                // Jika data tidak ditemukan, kembalikan respons error
                if (!$pengguna) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan.'
                    ]);
                }
            
                // Jika data ditemukan, tampilkan view
                return view('pengguna.show', ['pengguna' => $pengguna]);
            }
            public function confirm(string $id)
            {
                // Ambil user berdasarkan ID dan sertakan relasi dengan LevelModel
                $pengguna = PenggunaModel::with('jenisPengguna')->find($id);
        
                // Pastikan user ditemukan
                if (!$pengguna) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User tidak ditemukan.'
                    ]);
                }
        
                // Kirimkan data user ke view
                return view('pengguna.confirm', ['pengguna' => $pengguna]);
            }
        
            public function delete(Request $request, $id)
            {
                // Cek apakah request dari ajax
                if ($request->ajax() || $request->wantsJson()) {
                    $pengguna = PenggunaModel::find($id);
        
                    if ($pengguna) {
                        $pengguna->delete();
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
    public function import(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file yang diupload
            $rules = [
                'file_pengguna' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            // Mengambil file yang diupload
            $file = $request->file('file_pengguna');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
    
            // Menyiapkan data untuk dimasukkan ke dalam database
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        // Pastikan Anda memeriksa apakah file foto ada atau tidak, jika ada simpan ke folder
                        $fotoProfil = $value['F']; // Misal file path foto profil, Anda bisa melakukan pengecekan dan upload gambar di sini
                        $insert[] = [
                            'id_jenis_pengguna' => $value['A'], // Kolom A
                            'nama_pengguna' => $value['B'],      // Kolom B
                            'username' => $value['C'],           // Kolom C
                            'nip' => $value['D'],
                            'email' => $value['E'],              // Kolom E
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    // Insert data ke dalam tabel 'pengguna' jika ada data yang valid
                    PenggunaModel::insertOrIgnore($insert);
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
    // Mengambil data pengguna dari model PenggunaModel
    $pengguna = PenggunaModel::all();

    // Membuat objek spreadsheet baru
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set kolom header
    $sheet->setCellValue('A1', 'ID Jenis Pengguna');
    $sheet->setCellValue('B1', 'Nama Pengguna');
    $sheet->setCellValue('C1', 'Username');
    $sheet->setCellValue('D1', 'NIP');
    $sheet->setCellValue('E1', 'Email');
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);

    $baris = 2; // Dimulai dari baris kedua setelah header

    // Loop melalui data pengguna dan masukkan ke dalam spreadsheet
    foreach ($pengguna as $value) {
        $sheet->setCellValue('A' . $baris, $value->id_jenis_pengguna);  // ID Jenis Pengguna
        $sheet->setCellValue('B' . $baris, $value->nama_pengguna);      // Nama Pengguna
        $sheet->setCellValue('C' . $baris, $value->username);           // Username
        $sheet->setCellValue('D' . $baris, $value->nip);
        $sheet->setCellValue('E' . $baris, $value->email);              // Email

        $baris++;
    }

    // Menyesuaikan lebar kolom agar otomatis
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Memberi nama file sheet
    $sheet->setTitle('Data Pengguna');

    // Menyimpan file Excel
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_Pengguna_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Mengatur header HTTP untuk pengunduhan file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 25 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    // Menyimpan file ke output PHP
    $writer->save('php://output');
    exit;
}
public function export_pdf()
{
    // Mengambil data pengguna dari model PenggunaModel
    $pengguna = PenggunaModel::select('id_jenis_pengguna', 'nama_pengguna', 'username', 'email')
        ->orderBy('id_jenis_pengguna')  // Anda bisa sesuaikan pengurutan jika diperlukan
        ->get();

    // Menyiapkan data untuk view
    $pdf = Pdf::loadView('pengguna.export_pdf', ['pengguna' => $pengguna]);

    // Mengatur ukuran kertas A4 dengan orientasi portrait
    $pdf->setPaper('a4', 'portrait');

    // Menyajikan file PDF untuk diunduh dengan nama yang dinamis
    return $pdf->stream('Data Pengguna ' . date('Y-m-d H:i:s') . '.pdf');
}


}


