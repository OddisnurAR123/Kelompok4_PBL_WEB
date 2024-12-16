@if(!$jabatanKegiatan)
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
            <h5 class="modal-title" id="exampleModalLabel">Rincian Data Jabatan Kegiatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th style="width: 40%;">ID Jabatan Kegiatan:</th>
                    <td class="col-9">{{ $jabatanKegiatan->id_jabatan_kegiatan }}</td>
                </tr>
                <tr>
                    <th style="width: 40%;">Kode Jabatan Kegiatan:</th>
                    <td class="col-9">{{ $jabatanKegiatan->kode_jabatan_kegiatan }}</td>
                </tr>
                <tr>
                    <th style="width: 40%;">Nama Jabatan Kegiatan:</th>
                    <td class="col-9">{{ $jabatanKegiatan->nama_jabatan_kegiatan }}</td>
                </tr>
                <tr>
                    <th style="width: 40%;">Is PIC:</th>
                    <td class="col-9">{{ $jabatanKegiatan->is_pic }}</td>
                </tr>
                <tr>
                    <th style="width: 40%;">Urutan:</th>
                    <td class="col-9">{{ $jabatanKegiatan->urutan }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
@endif
