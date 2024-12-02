<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaModel;
use App\Models\JenisPenggunaModel;
use App\Models\KegiatanModel;
use App\Models\JabatanKegiatanModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;

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

    public function list(Request $request){
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
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();
        $kegiatan = KegiatanModel::select('id_kegiatan', 'nama_kegiatan')->get();
        $jabatanKegiatan = JabatanKegiatanModel::select('id_jabatan_kegiatan', 'nama_jabatan_kegiatan')->get();

        return view('agenda.create', compact('jenisPengguna', 'kegiatan', 'jabatanKegiatan'));
    }

    // Simpan data baru
    // public function store(Request $request)
    // {
    //     $rules = [
    //         'kode_agenda' => 'required|string|max:20|unique:t_agenda,kode_agenda',
    //         'nama_agenda' => 'required|string|max:255',
    //         'id_kegiatan' => 'required|exists:m_kegiatan,id_kegiatan',
    //         'tempat_agenda' => 'required|string|max:255',
    //         'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
    //         'id_jabatan_kegiatan' => 'nullable|exists:m_jabatan_kegiatan,id_jabatan_kegiatan',
    //         'bobot_anggota' => 'required|numeric|min:0',
    //         'deskripsi' => 'nullable|string',
    //         'tanggal_agenda' => 'required|date|date_format:Y-m-d H:i:s',
    //     ];
        

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validasi Gagal',
    //             'msgField' => $validator->errors(),
    //         ]);
    //     }

    //     $formattedDate = Carbon::parse($request->tanggal_agenda)->format('Y-m-d H:i:s');

    //     AgendaModel::create([
    //         'kode_agenda' => $request->kode_agenda,
    //         'nama_agenda' => $request->nama_agenda,
    //         'id_kegiatan' => $request->id_kegiatan,
    //         'tempat_agenda' => $request->tempat_agenda,
    //         'id_jenis_pengguna' => $request->id_jenis_pengguna,
    //         'id_jabatan_kegiatan' => $request->id_jabatan_kegiatan,
    //         'bobot_anggota' => $request->bobot_anggota,
    //         'deskripsi' => $request->deskripsi,
    //         'tanggal_agenda' => $formattedDate,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Data Agenda berhasil disimpan',
    //     ]);
    // }

    public function store(Request $request)
{
    $rules = [
        'kode_agenda' => 'required|string|max:20|unique:t_agenda,kode_agenda',
        'nama_agenda' => 'required|string|max:255',
        'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
        'tempat_agenda' => 'required|string|max:255',
        'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
        'id_jabatan_kegiatan' => 'nullable|exists:m_jabatan_kegiatan,id_jabatan_kegiatan',
        'bobot_anggota' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string',
        'tanggal_agenda' => 'required|date|date_format:Y-m-d H:i:s',
    ];

    $validated = $request->validate($rules);

    // Simpan data langsung ke database
    AgendaModel::create($validated);

    return response()->json([
        'status' => true,
        'message' => 'Data agenda berhasil disimpan.',
    ]);
}


    public function edit(string $id_agenda)
    {
        $agenda = AgendaModel::find($id_agenda);
    
        if (!$agenda) {
            return response()->json(['status' => false, 'message' => 'Data agenda tidak ditemukan']);
        }
    
        $jenisPengguna = JenisPenggunaModel::all(); // Ambil data jenis pengguna
        $kegiatan = KegiatanModel::all(); // Ambil data kegiatan
        $jabatanKegiatan = JabatanKegiatanModel::all(); // Ambil data jabatan kegiatan
    
        return view('agenda.edit', compact('agenda', 'jenisPengguna', 'kegiatan', 'jabatanKegiatan'));
    }
    public function update(Request $request, $id_agenda)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'kode_agenda' => 'required|string|max:20|unique:t_agenda,kode_agenda,' . $id_agenda . ',id_agenda',
            'nama_agenda' => 'required|string|max:100',
            'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
            'tempat_agenda' => 'required|string|max:255',
            'id_jenis_pengguna' => 'required|exists:m_jenis_pengguna,id_jenis_pengguna',
            'id_jabatan_kegiatan' => 'required|exists:m_jabatan_kegiatan,id_jabatan_kegiatan',
            'bobot_anggota' => 'required|numeric',
            'deskripsi' => 'required|string',
            'tanggal_agenda' => 'required|date',
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors(),
            ]);
        }

        $agenda = AgendaModel::find($id_agenda);

        if ($agenda) {
            $agenda->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data agenda berhasil diperbarui',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data agenda tidak ditemukan',
            ]);
        }
    }

    return redirect('/');
}
public function show(string $id)
{
    $agenda = AgendaModel::find($id);

    // Jika data tidak ditemukan, kembalikan respons error
    if (!$agenda) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ]);
    }

    // Jika data ditemukan, tampilkan view
    return view('agenda.show', ['agenda' => $agenda]);
} 
public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $agenda = AgendaModel::find($id);

            if ($agenda) {
                $agenda->delete();
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
    // Pastikan relasi yang diinginkan sudah benar
    $agenda = AgendaModel::with(['kegiatan', 'jenisPengguna', 'jabatanKegiatan'])->find($id);
    
    if (!$agenda) {
        return response()->json([
            'status' => false,
            'message' => 'Agenda tidak ditemukan.'
        ]);
    }

    return view('agenda.confirm', compact('agenda'));
}

public function import(Request $request) {
    if($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_agenda' => ['required', 'mimes:xlsx', 'max:1024'] 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_agenda');  // ambil file dari request
        $reader = IOFactory::createReader('Xlsx');  // load reader file excel
        $reader->setReadDataOnly(true);             // hanya membaca data
        $spreadsheet = $reader->load($file->getRealPath()); // load file excel
        $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif

        $data = $sheet->toArray(null, false, true, true);   // ambil data excel

        $insert = [];
        if (count($data) > 1) { // jika data lebih dari 1 baris
            foreach ($data as $baris => $value) {
                if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                    $insert[] = [
                        'kode_agenda' => $value['A'],
                        'nama_agenda' => $value['B'],
                        'nama_kegiatan' => $value['C'],
                        'tempat_agenda' => $value['D'],
                        'nama_jenis_pengguna' => $value['E'],
                        'nama_jabatan_kegiatan' => $value['F'],
                        'bobot_anggota' => $value['G'],
                        'deskripsi' => $value['H'],
                        'tanggal_agenda' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['I'])->format('Y-m-d'), // konversi tanggal jika diperlukan
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                // insert data ke database, jika data sudah ada, maka diabaikan
                AgendaModel::insertOrIgnore($insert);    
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
    $agenda = AgendaModel::select(
        'kode_agenda', 
        'nama_agenda' ,
        'nama_kegiatan' ,
        'tempat_agenda' ,
        'nama_jenis_pengguna' , 
        'nama_jabatan_kegiatan' ,
        'bobot_anggota' ,
        'deskripsi' ,
        'tanggal_agenda'
    )
    ->with(['kegiatan', 'jenisPengguna', 'jabatanKegiatan']) // Relasi dengan kategori
    ->get();

    // Load library PhpSpreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Agenda');
    $sheet->setCellValue('C1', 'Nama Agenda');
    $sheet->setCellValue('D1', 'Kegiatan');
    $sheet->setCellValue('E1', 'Tempat Agenda');
    $sheet->setCellValue('F1', 'Jenis Pengguna');
    $sheet->setCellValue('G1', 'Jabatan Kegiatan');
    $sheet->setCellValue('H1', 'Bobot Anggota');
    $sheet->setCellValue('I1', 'Deskripsi');
    $sheet->setCellValue('J1', 'Tanggal Agenda');


    // Buat header bold
    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    // Isi data kegiatan
    $no = 1; // Nomor urut
    $baris = 2; // Baris dimulai dari baris ke-2
    foreach ($agenda as $value) {
        $sheet->setCellValue('A' . $baris, $no); // No
        $sheet->setCellValue('B' . $baris, $value->kode_agenda); // Kode Agenda
        $sheet->setCellValue('C' . $baris, $value->nama_agenda); // Nama Agenda
        $sheet->setCellValue('D' . $baris, $value->kegiatan->nama_kegiatan ?? 'Tidak Ada Kegiatan'); // Kegiatan
        $sheet->setCellValue('E' . $baris, $value->tempat_agenda); // Tempat Agenda
        $sheet->setCellValue('F' . $baris, $value->jenisPengguna->nama_jenis_pengguna ?? 'Tidak Ditemukan'); // Jenis Pengguna
        $sheet->setCellValue('G' . $baris, $value->jabatanKegiatan->nama_jabatan_kegiatan ?? 'Tidak Ditemukan'); // Jabatan Kegiatan
        $sheet->setCellValue('H' . $baris, $value->bobot_anggota); // Bobot Anggota
        $sheet->setCellValue('I' . $baris, $value->deskripsi); // Deskripsi
        $sheet->setCellValue('J' . $baris, $value->tanggal_agenda); // Tanggal Agenda
        $baris++;
        $no++;
    }

    // Set auto width untuk kolom
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Set judul sheet
    $sheet->setTitle('Data Agenda');

    // Buat file Excel
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Agenda' . date('Y-m-d H:i:s') . '.xlsx';

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
    // Retrieve the agenda data you want to export to PDF
    $agenda = AgendaModel::all();

    // Pass the data to a view and generate the PDF
    $pdf = FacadePdf::loadView('agenda.pdf', compact('agenda'));

    // Return the PDF as a download
    return $pdf->download('agenda_report.pdf');
}

}
