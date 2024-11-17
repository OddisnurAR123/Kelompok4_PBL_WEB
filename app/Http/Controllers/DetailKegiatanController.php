<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DetailKegiatanController extends Controller
{
    public function index()
    {
        return view('detail_kegiatan.index');
    }
    public function list(Request $request)
{
    if ($request->ajax()) {
        // Ambil data kegiatan dari database
        $data = KegiatanModel::select(['id_kegiatan', 'nama_kegiatan', 'tanggal_kegiatan', 'lokasi_kegiatan', 'status_kegiatan']);
        
        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                // Tombol aksi untuk melihat detail kegiatan
                $btn = '<button onclick="modalAction(\'' . url('/detail-kegiatan/' . $row->id_kegiatan . '/show') . '\')" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true); // Mengirimkan data dalam format JSON
    }
}
}
