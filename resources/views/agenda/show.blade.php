@if(!$agenda)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/agenda') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Agenda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">Kode Agenda:</th>
                    <td class="col-9">{{ $agenda->kode_agenda }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Agenda:</th>
                    <td class="col-9">{{ $agenda->nama_agenda }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Kegiatan:</th>
                    <td class="col-9">{{ $agenda->kegiatan->nama_kegiatan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tempat Agenda:</th>
                    <td class="col-9">{{ $agenda->tempat_agenda }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jenis Pengguna:</th>
                    <td class="col-9">{{ $agenda->jenisPengguna->nama_jenis_pengguna }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jabatan Kegiatan:</th>
                    <td class="col-9">
                        {{ $agenda->jabatanKegiatan->nama_jabatan_kegiatan}}
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Bobot Anggota:</th>
                    <td class="col-9">{{ $agenda->bobot_anggota }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Agenda:</th>
                    <td class="col-9">{{ $agenda->tanggal_agenda }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi:</th>
                    <td class="col-9">{{ $agenda->deskripsi ?? 'Tidak Ada Deskripsi' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
@endif
