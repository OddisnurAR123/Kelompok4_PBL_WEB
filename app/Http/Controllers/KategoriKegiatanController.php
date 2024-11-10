<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KategoriKegiatan;
use Illuminate\Support\Facades\Validator;

class KategoriKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori Kegiatan',
            'list' => ['Home', 'Kategori Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar Kategori Kegiatan yang Ada'
        ];

        $activeMenu = 'kategori';

        return view('kategori.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Fetch data for DataTables.
     */
    public function list(Request $request) {
        $kategoris = KategoriKegiatan::select('id_kategori_kegiatan', 'kode_kategori_kegiatan', 'nama_kategori_kegiatan');

        return DataTables::of($kategoris)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<button onclick="modalAction(\''.url('/kategori-kegiatan/' . $kategori->id_kategori_kegiatan . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori-kegiatan/' . $kategori->id_kategori_kegiatan . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori-kegiatan/' . $kategori->id_kategori_kegiatan . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori Kegiatan',
            'list' => ['Home', 'Kategori Kegiatan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Kategori Kegiatan Baru'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'kode_kategori_kegiatan'  => 'required|string|unique:m_kategori_kegiatan,kode_kategori_kegiatan',
            'nama_kategori_kegiatan'  => 'required|string|max:100',
        ]);

        KategoriKegiatan::create([
            'kode_kategori_kegiatan' => $request->kode_kategori_kegiatan,
            'nama_kategori_kegiatan' => $request->nama_kategori_kegiatan
        ]);

        return redirect('/kategori-kegiatan')->with('success', 'Data kategori kegiatan berhasil disimpan.');
    }

    /**
     * Show the form for creating a new resource via AJAX.
     */
    public function create_ajax() {
        return view('kategori.create_ajax');
    }

    /**
     * Store a newly created resource via AJAX.
     */
    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori_kegiatan' => 'required|string|min:3|unique:m_kategori_kegiatan,kode_kategori_kegiatan',
                'nama_kategori_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            KategoriKegiatan::create([
                'kode_kategori_kegiatan' => $request->kode_kategori_kegiatan,
                'nama_kategori_kegiatan' => $request->nama_kategori_kegiatan,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kategori kegiatan berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    /**
     * Display the specified resource via AJAX.
     */
    public function show_ajax(string $id) {
        $kategori = KategoriKegiatan::find($id);

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Data kategori kegiatan tidak ditemukan.'
            ]);
        }

        return view('kategori.show_ajax', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource via AJAX.
     */
    public function edit_ajax(string $id) {
        $kategori = KategoriKegiatan::find($id);

        if (!$kategori) {
            return response()->json(['status' => false, 'message' => 'Data kategori kegiatan tidak ditemukan']);
        }

        return view('kategori.edit_ajax', compact('kategori'));
    }

    /**
     * Update the specified resource via AJAX.
     */
    public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori_kegiatan' => 'required|string|max:20|unique:m_kategori_kegiatan,kode_kategori_kegiatan,' . $id . ',id_kategori_kegiatan',
                'nama_kategori_kegiatan' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal!',
                    'msgField' => $validator->errors()
                ]);
            }

            $kategori = KategoriKegiatan::find($id);

            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kategori kegiatan tidak ditemukan'
                ]);
            }

            $kategori->kode_kategori_kegiatan = $request->kode_kategori_kegiatan;
            $kategori->nama_kategori_kegiatan = $request->nama_kategori_kegiatan;
            $kategori->save();

            return response()->json([
                'status' => true,
                'message' => 'Data kategori kegiatan berhasil diupdate!'
            ]);
        }

        return redirect('/');
    }

    /**
     * Show confirmation modal via AJAX.
     */
    public function confirm_ajax(string $id) {
        $kategori = KategoriKegiatan::find($id);

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Data kategori kegiatan tidak ditemukan.'
            ]);
        }

        return view('kategori.confirm_ajax', compact('kategori'));
    }

    /**
     * Delete the specified resource via AJAX.
     */
    public function delete_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriKegiatan::find($id);

            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori kegiatan berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kategori kegiatan tidak ditemukan',
                ]);
            }
        }

        return redirect('/');
    }

    /**
     * Show the import form.
     */
    public function import() {
        $breadcrumb = (object) [
            'title' => 'Import Kategori Kegiatan',
            'list' => ['Home', 'Kategori Kegiatan', 'Import']
        ];

        $page = (object) [
            'title' => 'Import Kategori Kegiatan dari Excel'
        ];

        $activeMenu = 'kategori';

        return view('kategori.import', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Handle the import via AJAX.
     */
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kategori_kegiatan' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_kategori_kegiatan');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'kode_kategori_kegiatan' => $value['A'],
                            'nama_kategori_kegiatan' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    KategoriKegiatan::insertOrIgnore($insert);
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

    /**
     * Export data to Excel.
     */
    public function export_excel() {
        $kategori = KategoriKegiatan::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori Kegiatan');
        $sheet->setCellValue('C1', 'Nama Kategori Kegiatan');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;

        foreach ($kategori as $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kode_kategori_kegiatan);
            $sheet->setCellValue('C' . $baris, $value->nama_kategori_kegiatan);
            $baris++;
            $no++;
        }

        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Kategori Kegiatan');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Kategori_Kegiatan_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 25 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export data to PDF.
     */
    public function export_pdf() {
        $kategori = KategoriKegiatan::all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kategori.export_pdf', compact('kategori'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data_Kategori_Kegiatan_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
