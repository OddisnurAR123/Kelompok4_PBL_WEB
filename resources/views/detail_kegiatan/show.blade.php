@if($detailKegiatan)
    <!-- Modal untuk detail kegiatan jika data ada -->
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th style="width: 40%;">ID Detail Kegiatan:</th>
                        <td class="col-9">{{ $detailKegiatan->id_detail_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">ID Kegiatan:</th>
                        <td class="col-9">{{ $detailKegiatan->id_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Progres Kegiatan:</th>
                        <td class="col-9">{{ $detailKegiatan->progres_kegiatan }}%</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Keterangan:</th>
                        <td class="col-9">{{ $detailKegiatan->keterangan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Beban Kerja:</th>
                        <td class="col-9">{{ $detailKegiatan->beban_kerja }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@else
    <!-- Modal untuk kesalahan jika data tidak ditemukan -->
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
@endif