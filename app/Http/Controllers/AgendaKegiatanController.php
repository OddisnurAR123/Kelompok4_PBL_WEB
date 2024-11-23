<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaModel;
use App\Models\JabatanKegiatanModel;
use App\Models\JenisPenggunaModel;
use App\Models\KegiatanModel;

class AgendaKegiatanController extends Controller
{
    // Menampilkan semua agenda kegiatan
    public function index()
    {
        // Data agenda dan jenis pengguna
        $agendas = AgendaModel::all(); 
        $jenisPenggunas = JenisPenggunaModel::all();
    
        // Tambahkan breadcrumb
        $breadcrumb = (object) [
            'title' => 'Daftar Agenda',
            'list' => [
                (object) ['label' => 'Dashboard', 'url' => url('/')],
                (object) ['label' => 'Agenda', 'url' => url('/agenda')],
                'Daftar Agenda'
            ]
        ];
    
        // Kirim data ke view
        return view('agenda.index', compact('agendas', 'jenisPenggunas', 'breadcrumb')); 
    }    

    // Menyimpan agenda yang baru dibuat
    public function store(Request $request)
    {
        // Validasi data dari form
        $validated = $request->validate([
            'kode_agenda' => 'required|string|max:255',
            'nama_agenda' => 'required|string|max:255',
            'id_kegiatan' => 'required|integer|exists:kegiatans,id_kegiatan',
            'tempat_agenda' => 'required|string|max:255',
            'id_jenis_pengguna' => 'required|integer|exists:jenis_penggunas,id_jenis_pengguna',
            'id_jabatan_kegiatan' => 'required|integer|exists:jabatan_kegiatans,id_jabatan_kegiatan',
            'bobot_anggota' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'tanggal_agenda' => 'required|date',
        ]);

        // Simpan agenda baru ke database
        AgendaModel::create($validated); 
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit agenda
    public function edit($id)
    {
        $agenda = AgendaModel::findOrFail($id); // Cari agenda berdasarkan ID
        $kegiatans = KegiatanModel::all(); // Ambil data kegiatan
        $jenisPenggunas = JenisPenggunaModel::all(); // Ambil data jenis pengguna
        $jabatanKegiatans = JabatanKegiatanModel::all(); // Ambil data jabatan kegiatan

        return view('agenda.edit', compact('agenda', 'kegiatans', 'jenisPenggunas', 'jabatanKegiatans')); 
    }

    // Memperbarui agenda
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_agenda' => 'required|string|max:255',
            'nama_agenda' => 'required|string|max:255',
            'id_kegiatan' => 'required|integer|exists:kegiatans,id_kegiatan',
            'tempat_agenda' => 'required|string|max:255',
            'id_jenis_pengguna' => 'required|integer|exists:jenis_penggunas,id_jenis_pengguna',
            'id_jabatan_kegiatan' => 'required|integer|exists:jabatan_kegiatans,id_jabatan_kegiatan',
            'bobot_anggota' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'tanggal_agenda' => 'required|date',
        ]);

        $agenda = AgendaModel::findOrFail($id); // Cari agenda berdasarkan ID
        $agenda->update($validated); // Perbarui agenda
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui!');
    }

    // Menghapus agenda
    public function destroy($id)
    {
        $agenda = AgendaModel::findOrFail($id); // Cari agenda berdasarkan ID
        $agenda->delete(); // Hapus agenda
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus!');
    }
    public function create_ajax()
    {
        // Ambil data yang diperlukan untuk form
        $kegiatans = KegiatanModel::select('id_kegiatan', 'nama_kegiatan')->get(); 
        $jenisPenggunas = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();
        $jabatanKegiatans = JabatanKegiatanModel::select('id', 'nama_jabatan')->get();
    
        // Mengirimkan response dalam bentuk HTML untuk modal form
        return view('agenda.create_ajax', compact('kegiatans', 'jenisPenggunas', 'jabatanKegiatans'));
    }
    

}
