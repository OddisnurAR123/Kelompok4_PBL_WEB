<?php

namespace App\Http\Controllers;

use App\Models\DetailAgendaModel;
use App\Models\AgendaModel;
use App\Models\DetailKegiatanModel;
use App\Models\KegiatanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DetailAgendaController extends Controller
{

    public function create($id_kegiatan, $id_agenda)
    {
        // Ambil data kegiatan dan agenda berdasarkan ID
        $kegiatan = KegiatanModel::find($id_kegiatan);
        $agenda = AgendaModel::find($id_agenda);

        // Pastikan data ditemukan sebelum melanjutkan
        if (!$kegiatan || !$agenda) {
            return redirect()->route('detail_agenda.create')->with('error', 'Kegiatan atau Agenda tidak ditemukan.');
        }

        // Kirim data ke view
        return view('detail_agenda.create', compact('kegiatan', 'agenda'));
    }
  
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
            'id_agenda' => 'required|exists:t_agenda,id_agenda',
            'keterangan' => 'required|min:3|max:100',
            'progres_agenda' => 'required|numeric|min:0|max:100',
            'berkas' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:2048',
        ]);
    

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Ada kesalahan pada input.',
                'msgField' => $validator->errors()
            ]);
        }
    
        $detailAgenda = new DetailAgendaModel();
        $detailAgenda->id_kegiatan = $request->id_kegiatan;
        $detailAgenda->id_agenda = $request->id_agenda;
        $detailAgenda->keterangan = $request->keterangan;
        $detailAgenda->progres_agenda = $request->progres_agenda;
    
        if ($request->hasFile('berkas')) {
            $berkas = $request->file('berkas');
        
            $mimeType = $berkas->getMimeType();
            $extension = $berkas->getClientOriginalExtension();
        
            $validMimeTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/png',
                'text/plain'
            ];
            
            if (!in_array($mimeType, $validMimeTypes)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Format file tidak didukung. MIME type tidak valid.'
                ]);
            }

            $fileName = time() . '.' . $extension;
    
            $berkas->storeAs('public/berkas', $fileName);

            $detailAgenda->berkas = 'storage/berkas/' . $fileName;
        }

        $detailAgenda->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Progres Agenda berhasil disimpan.'
        ]);
    }

    // Menampilkan detail detail kegiatan
    public function show(string $id) {
        $detailAgenda = DetailAgendaModel::find($id);

        if (!$detailAgenda) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('detail_agenda.show', ['detailAgenda' => $detailAgenda]);
    }   

    public function edit($id)
    {
        $detailAgenda = DetailAgendaModel::findOrFail($id);
        $kegiatan = KegiatanModel::all();
        $agenda = AgendaModel::all();
        return view('detail_agenda.edit', compact('detailAgenda', 'kegiatan', 'agenda'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kegiatan' => 'nullable',
            'id_agenda' => 'nullable',
            'keterangan' => 'nullable|min:3',
            'progres_agenda' => 'nullable|numeric|min:0|max:100',
            'berkas' => 'nullable|mimes:pdf,doc,docx,jpg,png,txt|max:2048'
        ]);

        $detailAgenda = DetailAgendaModel::findOrFail($id);
        
        // Cek dan update hanya jika ada perubahan
        if ($request->has('id_kegiatan')) {
            $detailAgenda->id_kegiatan = $request->id_kegiatan;
        }
        
        if ($request->has('id_agenda')) {
            $detailAgenda->id_agenda = $request->id_agenda;
        }
        
        if ($request->has('keterangan')) {
            $detailAgenda->keterangan = $request->keterangan;
        }

        if ($request->has('progres_agenda')) {
            $detailAgenda->progres_agenda = $request->progres_agenda;
        }
        
        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $filePath = $file->store('berkas', 'public');
            $detailAgenda->berkas = $filePath;
        }

        $detailAgenda->save();

        return response()->json([
            'status' => true,
            'message' => 'Progres agenda berhasil diperbarui'
        ]);
    }
    public function upgrade($id_kegiatan, $id_agenda)
    {
        // Cek apakah sudah ada detail untuk agenda tersebut
        $detailAgenda = DetailAgendaModel::where('id_kegiatan', $id_kegiatan)
                                        ->where('id_agenda', $id_agenda)
                                        ->first();

        // Jika detail agenda ada, arahkan ke halaman edit
        if ($detailAgenda) {
            return redirect()->route('detail_agenda.edit', ['id' => $detailAgenda->id_detail_agenda]);
        }

        // Jika detail agenda belum ada, arahkan ke halaman create
        return redirect()->route('detail_agenda.create', ['id_kegiatan' => $id_kegiatan, 'id_agenda' => $id_agenda]);
    }
}