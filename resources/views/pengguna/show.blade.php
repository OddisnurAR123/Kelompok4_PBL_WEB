@if(!$pengguna)
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
            <a href="{{ url()->previous() }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">ID Pengguna:</th>
                    <td class="col-9">{{ $pengguna->id_pengguna }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Pengguna:</th>
                    <td class="col-9">{{ $pengguna->nama_pengguna }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Username:</th>
                    <td class="col-9">{{ $pengguna->username }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Email:</th>
                    <td class="col-9">{{ $pengguna->email }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jenis Pengguna:</th>
                    <td class="col-9">{{ $pengguna->jenisPengguna->nama_jenis_pengguna ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Foto Profil:</th>
                    <td class="col-9">
                        @if($pengguna->foto_profil)
                            <img src="{{ asset('storage/' . $pengguna->foto_profil) }}" class="img-fluid img-thumbnail" width="150">
                        @else
                            <span class="text-muted">No photo available</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
@endif
