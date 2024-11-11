<?php

namespace App\Http\Controllers;

use App\Models\PenggunaModel;  // Adjusted to reflect the new model name
use App\Models\JenisPenggunaModel;  // Changed LevelModel to JenisPenggunaModel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    // Menampilkan halaman awal pengguna
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar pengguna yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengguna'; // set menu yang sedang aktif

        $jenisPengguna = JenisPenggunaModel::all(); // ambil data jenis pengguna untuk filter level

        return view('pengguna.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenisPengguna' => $jenisPengguna,  // Changed to jenisPengguna
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data pengguna dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $pengguna = PenggunaModel::select('id_pengguna', 'username', 'nama_pengguna', 'level_id')
            ->with('level');
        // Filter data pengguna berdasarkan level_id
        if ($request->level_id) {
            $pengguna->where('level_id', $request->level_id);
        }
        return DataTables::of($pengguna)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addColumn('aksi', function ($pengguna) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Pengguna',
            'list' => ['Home', 'Pengguna', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah pengguna baru'
        ];

        $jenisPengguna = JenisPenggunaModel::all(); // ambil data jenis pengguna untuk ditampilkan di form
        $activeMenu = 'pengguna'; // set menu yang sedang aktif

        return view('pengguna.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenisPengguna' => $jenisPengguna,  // Changed to jenisPengguna
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirimkan dari form
        $request->validate([
            'username' => 'required|string|min:3|unique:m_pengguna,username', // Username harus diisi, minimal 3 karakter, dan unik
            'nama_pengguna' => 'required|string|max:100', // Nama harus diisi, maksimal 100 karakter
            'password' => 'required|min:5', // Password harus diisi, minimal 5 karakter
            'level_id' => 'required|integer', // Level ID harus diisi dan berupa angka
        ]);

        // Simpan data pengguna ke database
        PenggunaModel::create([
            'username' => $request->username,
            'nama_pengguna' => $request->nama_pengguna,
            'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
            'level_id' => $request->level_id,
        ]);

        // Redirect kembali ke halaman pengguna dengan pesan sukses
        return redirect('/pengguna')->with('success', 'Data pengguna berhasil disimpan');
    }

    // Menampilkan detail pengguna
    public function show(string $id)
    {
        $pengguna = PenggunaModel::with('level')->find($id);

        if (!$pengguna) {
            return redirect('/pengguna')->with('error', 'Pengguna tidak ditemukan.'); // Redirect with error message if pengguna not found
        }

        $breadcrumb = (object) [
            'title' => 'Detail Pengguna',
            'list' => ['Home', 'Pengguna', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail pengguna',
        ];

        $activeMenu = 'pengguna'; // set menu yang sedang aktif

        return view('pengguna.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'pengguna' => $pengguna,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function edit(string $id)
    {
        // Temukan data pengguna berdasarkan ID
        $pengguna = PenggunaModel::find($id);
        // Ambil semua data jenis pengguna
        $jenisPengguna = JenisPenggunaModel::all();  // Changed to JenisPenggunaModel

        // Buat data untuk breadcrumb
        $breadcrumb = (object) [
            'title' => 'Edit Pengguna',
            'list' => ['Home', 'Pengguna', 'Edit'],
        ];

        // Buat data untuk halaman
        $page = (object) [
            'title' => 'Edit Pengguna',
        ];
        // Set menu aktif
        $activeMenu = 'pengguna';
        // Render view dengan data yang telah disiapkan
        return view('pengguna.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'pengguna' => $pengguna,
            'jenisPengguna' => $jenisPengguna,  // Changed to jenisPengguna
            'activeMenu' => $activeMenu,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_pengguna,username,' . $id . ',id_pengguna',
            'nama_pengguna' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
        ]);

        PenggunaModel::find($id)->update([
            'username' => $request->username,
            'nama_pengguna' => $request->nama_pengguna,
            'password' => $request->password ? bcrypt($request->password) : PenggunaModel::find($id)->password,
            'level_id' => $request->level_id,
        ]);

        return redirect('/pengguna')->with('success', 'Data pengguna berhasil diubah');
    }

    // Menghapus data pengguna
    public function destroy(string $id)
    {
        // Cari data pengguna berdasarkan ID
        $pengguna = PenggunaModel::find($id);

        // Jika data pengguna tidak ditemukan, tampilkan pesan error
        if (!$pengguna) {
            return redirect('/pengguna')->with('error', 'Data pengguna tidak ditemukan');
        }

        try {
            // Hapus data pengguna
            PenggunaModel::destroy($id);

            // Jika berhasil, tampilkan pesan sukses
            return redirect('/pengguna')->with('success', 'Data pengguna berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error saat menghapus, tampilkan pesan error
            return redirect('/pengguna')->with('error', 'Data pengguna gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax($id)
    {
        $pengguna = PenggunaModel::find($id);

        return view('pengguna.show_ajax', ['pengguna' => $pengguna]);
    }

    public function create_ajax()
    {
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();  // Changed to JenisPenggunaModel

        return view('pengguna.create_ajax')
            ->with('jenisPengguna', $jenisPengguna);  // Changed to jenisPengguna
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_pengguna,username',
                'nama_pengguna' => 'required|string|max:100',
                'password' => 'required|min:6',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            PenggunaModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data pengguna berhasil disimpan',
            ]);
        }

        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $pengguna = PenggunaModel::find($id);
        $jenisPengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();  // Changed to JenisPenggunaModel

        return view('pengguna.edit_ajax', ['pengguna' => $pengguna, 'jenisPengguna' => $jenisPengguna]);  // Changed to jenisPengguna
    }
}
