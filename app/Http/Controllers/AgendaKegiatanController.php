<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanUser;
use App\Models\PenggunaModel;
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
    $rules = [
        'nama_agenda' => 'required|string|max:255',
        'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
        'tempat_agenda' => 'required|string|max:255',
        'id_pengguna' => 'nullable|exists:t_kegiatan_user,id_pengguna',
        'bobot_anggota' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string',
        'tanggal_agenda' => 'required|date|date_format:Y-m-d\TH:i',
    ];

    $validated = $request->validate($rules);

    try {
        AgendaModel::create($validated);
        return response()->json(['status' => true, 'message' => 'Data agenda berhasil disimpan.']);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
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
        }

        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan',
        ]);
    }

    return redirect('/agenda');
}

public function confirm(string $id)
{
    $agenda = AgendaModel::with(['kegiatan', 'kegiatanUser'])->find($id);

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

                        'nama_agenda' => $value['B'],
                        'nama_kegiatan' => $value['C'],
                        'tempat_agenda' => $value['D'],
                        'nama_pengguna' => $value['E'],
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
        'nama_agenda' ,
        'nama_kegiatan' ,
        'tempat_agenda' ,
        'nama_pengguna' , 
        'nama_jabatan_kegiatan' ,
        'bobot_anggota' ,
        'deskripsi' ,
        'tanggal_agenda'
    )
    ->with(['kegiatan', 'kegiatanUser']) // Relasi dengan kategori
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
    $sheet->setCellValue('F1', 'Nama Pengguna');
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
        $sheet->setCellValue('F' . $baris, $value->kegiatanUser->nama_pengguna ?? 'Tidak Ditemukan'); // Jenis Pengguna
        $sheet->setCellValue('G' . $baris, $value->kegiatanUser->nama_jabatan_kegiatan ?? 'Tidak Ditemukan'); // Jabatan Kegiatan
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
public function getAgendaByKegiatan($id)
{
    // Ambil agenda berdasarkan id_kegiatan
    $agendas = AgendaModel::where('id_kegiatan', $id)->get();
    
    if ($agendas->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada agenda ditemukan untuk kegiatan ini'
        ]);
    }

    return response()->json([
        'success' => true,
        'agendas' => $agendas
    ]);
}
}