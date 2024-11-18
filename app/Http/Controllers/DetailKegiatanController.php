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
    public function create()
{
    return view('detail_kegiatan.create');
}
public function store(Request $request)
{
    // Validasi data
    $validated = $request->validate([
        'nama_kegiatan' => 'required|string|max:255',
        'tanggal_kegiatan' => 'required|date',
        'lokasi_kegiatan' => 'required|string|max:255',
        'status_kegiatan' => 'required|string|in:aktif,selesai,dibatalkan',
    ]);

    // Simpan data ke dalam database
    $kegiatan = new KegiatanModel();
    $kegiatan->nama_kegiatan = $validated['nama_kegiatan'];
    $kegiatan->tanggal_kegiatan = $validated['tanggal_kegiatan'];
    $kegiatan->lokasi_kegiatan = $validated['lokasi_kegiatan'];
    $kegiatan->status_kegiatan = $validated['status_kegiatan'];
    $kegiatan->save();

    // Redirect ke halaman detail kegiatan dengan pesan sukses
    return redirect()->route('detail_kegiatan.index')->with('success', 'Detail Kegiatan berhasil ditambahkan');
}

    public function list(Request $request)
{
    if ($request->ajax()) {
        // Ambil data kegiatan dari database
        $data = KegiatanModel::select(['id_kegiatan', 'nama_kegiatan']);
        
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
