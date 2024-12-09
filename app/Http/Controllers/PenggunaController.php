<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use App\Models\JenisPenggunaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;



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
                $btn = '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/show').'\')" class="btn btn-info btn-sm">';
                $btn .= '<i class="fas fa-eye"></i></button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/edit').'\')" class="btn btn-warning btn-sm">';
                $btn .= '<i class="fas fa-edit"></i></button> ';
                $btn .= '<button onclick="modalAction(\''.url('/pengguna/' . $pengguna->id_pengguna . '/delete').'\')" class="btn btn-danger btn-sm">';
                $btn .= '<i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['foto', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();
        return view('pengguna.create', compact('jenisPengguna'));
    }

    public function store(Request $request)
    {
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
            'password' => bcrypt($request->password),
            'nip' => $request->nip,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pengguna berhasil ditambahkan.',
        ]);
    }

    public function edit($id_pengguna)
    {
        $pengguna = PenggunaModel::find($id_pengguna);
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();

        return view('pengguna.edit', compact('pengguna', 'jenisPengguna'));
    }

    public function update(Request $request, $id_pengguna)
    {
        $rules = [
            'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
            'nama_pengguna' => 'required|string',
            'username' => 'required|string|unique:m_pengguna,username,' . $id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:6',
            'nip' => 'required|string',
            'email' => 'required|email|unique:m_pengguna,email,' . $id_pengguna . ',id_pengguna',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $pengguna = PenggunaModel::find($id_pengguna);
        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $pengguna->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.',
        ]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pengguna = PenggunaModel::find($id);
            if ($pengguna) {
                $pengguna->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
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
            public function import_ajax(Request $request) {
                if ($request->ajax() || $request->wantsJson()) {
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
            
                    $file = $request->file('file_pengguna');
                    $reader = IOFactory::createReader('Xlsx');
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($file->getRealPath());
                    $sheet = $spreadsheet->getActiveSheet();
            
                    $data = $sheet->toArray(null, false, true, true);
            
                    $insert = [];
                    if (count($data) > 1) {
                        foreach ($data as $baris => $value) {
                            if ($baris > 1) {
                                $insert[] = [
                                    'id_jenis_pengguna' => $value['A'],
                                    'nama_pengguna' => $value['B'],
                                    'username' => $value['C'],
                                    'password' => bcrypt($value['D']), // Hash password
                                    'nip' => $value['E'],
                                    'email' => $value['F'],
                                    'created_at' => now(),
                                ];
                            }
                        }
            
                        if (count($insert) > 0) {
                            PenggunaModel::insertOrIgnore($insert);
                        }
            
                        return response()->json([
                            'status' => true,
                            'message' => 'Data pengguna berhasil diimport'
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
            
            public function export_excel() {
                $pengguna = PenggunaModel::select(
                    'id_jenis_pengguna',
                    'nama_pengguna',
                    'username',
                    'nip',
                    'email'
                )->get();
            
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
            
                // Header kolom
                $sheet->setCellValue('A1', 'No');
                $sheet->setCellValue('B1', 'ID Jenis Pengguna');
                $sheet->setCellValue('C1', 'Nama Pengguna');
                $sheet->setCellValue('D1', 'Username');
                $sheet->setCellValue('E1', 'NIP');
                $sheet->setCellValue('F1', 'Email');
            
                $sheet->getStyle('A1:F1')->getFont()->setBold(true);
            
                // Isi data pengguna
                $no = 1;
                $baris = 2;
                foreach ($pengguna as $value) {
                    $sheet->setCellValue('A' . $baris, $no);
                    $sheet->setCellValue('B' . $baris, $value->id_jenis_pengguna);
                    $sheet->setCellValue('C' . $baris, $value->nama_pengguna);
                    $sheet->setCellValue('D' . $baris, $value->username);
                    $sheet->setCellValue('E' . $baris, $value->nip);
                    $sheet->setCellValue('F' . $baris, $value->email);
                    $baris++;
                    $no++;
                }
            
                foreach (range('A', 'F') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            
                $sheet->setTitle('Data Pengguna');
            
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $filename = 'Data Pengguna ' . date('Y-m-d H:i:s') . '.xlsx';
            
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
            
                $writer->save('php://output');
                exit;
            }
            

            public function export_pdf() {
                $pengguna = PenggunaModel::select(
                    'id_jenis_pengguna',
                    'nama_pengguna',
                    'username',
                    'nip',
                    'email'
                )->orderBy('nama_pengguna')->get();
            
                $pdf = Pdf::loadView('pengguna.export_pdf', ['pengguna' => $pengguna]);
            
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOption("isRemoteEnabled", true);
            
                return $pdf->stream('Data Pengguna ' . date('Y-m-d H:i:s') . '.pdf');
            }
            
}


