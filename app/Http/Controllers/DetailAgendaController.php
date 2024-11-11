<?php

namespace App\Http\Controllers;

use App\Models\DetailAgenda;
use App\Models\AgendaModel;
use Illuminate\Http\Request;

class DetailAgendaController extends Controller
{
    // Menampilkan daftar detail agenda berdasarkan agenda tertentu
    public function index($agendaId)
    {
        // Cari agenda berdasarkan ID
        $agenda = AgendaModel::findOrFail($agendaId);
        // Ambil semua detail agenda terkait agenda ini
        $details = $agenda->detailAgenda; 

        return view('detail_agenda.index', compact('details', 'agenda')); // Kirim data ke view
    }

    // Menampilkan form untuk membuat detail agenda baru
    public function create($agendaId)
    {
        $agenda = AgendaModel::findOrFail($agendaId); // Cari agenda berdasarkan ID
        return view('detail_agenda.create', compact('agenda'));
    }

    // Menyimpan detail agenda yang baru dibuat
    public function store(Request $request, $agendaId)
    {
        // Validasi data dari request
        $validated = $request->validate([
            'dokumen' => 'nullable|string|max:255',
            'progres_agenda' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'berkas' => 'nullable|file|mimes:pdf,docx,jpg,png', // Validasi file
        ]);

        // Tambahkan id_agenda ke data yang akan disimpan
        $validated['id_agenda'] = $agendaId;

        // Simpan detail agenda baru ke database
        DetailAgenda::create($validated);

        return redirect()->route('detailAgenda.index', $agendaId)->with('success', 'Detail agenda berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit detail agenda
    public function edit($agendaId, $id)
    {
        $detail = DetailAgenda::findOrFail($id); // Cari detail agenda berdasarkan ID
        $agenda = AgendaModel::findOrFail($agendaId); // Cari agenda berdasarkan ID

        return view('detail_agenda.edit', compact('detail', 'agenda'));
    }

    // Memperbarui detail agenda
    public function update(Request $request, $agendaId, $id)
    {
        // Validasi data dari request
        $validated = $request->validate([
            'dokumen' => 'nullable|string|max:255',
            'progres_agenda' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'berkas' => 'nullable|file|mimes:pdf,docx,jpg,png',
        ]);

        $detail = DetailAgenda::findOrFail($id); // Cari detail agenda berdasarkan ID
        $detail->update($validated); // Perbarui detail agenda

        return redirect()->route('detailAgenda.index', $agendaId)->with('success', 'Detail agenda berhasil diperbarui!');
    }

    // Menghapus detail agenda
    public function destroy($agendaId, $id)
    {
        $detail = DetailAgenda::findOrFail($id); // Cari detail agenda berdasarkan ID
        $detail->delete(); // Hapus detail agenda

        return redirect()->route('detailAgenda.index', $agendaId)->with('success', 'Detail agenda berhasil dihapus!');
    }
}
