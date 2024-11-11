<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda; // Model untuk tabel t_agenda
use App\Models\AgendaModel;
use App\Models\DetailAgenda; // Model untuk tabel t_detail_agenda
use Illuminate\Support\Facades\Validator;

class AgendaKegiatanController extends Controller
{
    // Menampilkan semua agenda
    public function index()
    {
        $agendas = AgendaModel::with('detailAgenda')->get();
        return response()->json($agendas);
    }

    // Menyimpan agenda baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_agenda' => 'required|string|max:50',
            'nama_agenda' => 'required|string|max:100',
            'id_kegiatan' => 'required|integer',
            'tempat_agenda' => 'nullable|string|max:255',
            'id_jenis_pengguna' => 'required|integer',
            'id_jabatan_kegiatan' => 'required|integer',
            'bobot_anggota' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'tanggal_agenda' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $agenda = AgendaModel::create($request->all());
        return response()->json($agenda, 201);
    }

    // Menampilkan detail agenda berdasarkan id
    public function show($id)
    {
        $agenda = AgendaModel::with('detailAgenda')->find($id);
        if (!$agenda) {
            return response()->json(['message' => 'Agenda not found'], 404);
        }
        return response()->json($agenda);
    }

    // Mengupdate agenda berdasarkan id
    public function update(Request $request, $id)
    {
        $agenda = AgendaModel::find($id);
        if (!$agenda) {
            return response()->json(['message' => 'Agenda not found'], 404);
        }

        $agenda->update($request->all());
        return response()->json($agenda);
    }

    // Menghapus agenda berdasarkan id
    public function destroy($id)
    {
        $agenda = AgendaModel::find($id);
        if (!$agenda) {
            return response()->json(['message' => 'Agenda not found'], 404);
        }

        $agenda->delete();
        return response()->json(['message' => 'Agenda deleted successfully']);
    }

    // Menyimpan detail agenda baru
    public function storeDetailAgenda(Request $request, $id_agenda)
    {
        $validator = Validator::make($request->all(), [
            'dokumen' => 'nullable|string',
            'progres_agenda' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
            'berkas' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $detailAgenda = new DetailAgenda();
        $detailAgenda->id_agenda = $id_agenda;
        $detailAgenda->dokumen = $request->dokumen;
        $detailAgenda->progres_agenda = $request->progres_agenda;
        $detailAgenda->keterangan = $request->keterangan;

        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $path = $file->store('uploads/berkas', 'public');
            $detailAgenda->berkas = $path;
        }

        $detailAgenda->save();
        return response()->json($detailAgenda, 201);
    }
}
