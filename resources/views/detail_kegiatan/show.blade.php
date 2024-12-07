<!-- Modal -->
<div id="modal-master" class="modal-dialog modal-lg modal-shake" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                @if(!$detailKegiatan)
                    Kesalahan
                @else
                    Detail Kegiatan
                @endif
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if(!$detailKegiatan)
                <div class="alert alert-danger text-center">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th style="width: 40%;">ID Detail Kegiatan:</th>
                        <td class="col-9">{{ $detailKegiatan->id_detail_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Nama Kegiatan:</th>
                        <td class="col-9">{{ $detailKegiatan->kegiatan->nama_kegiatan }}</td>
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
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>

<!-- CSS untuk Animasi -->
<style>
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        50% { transform: translateX(10px); }
        75% { transform: translateX(-10px); }
        100% { transform: translateX(0); }
    }

    .modal-shake {
        animation: shake 0.5s ease-in-out;
    }

    .modal-content {
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #01274E;
        color: #fff;
        border-bottom: none;
    }

    .modal-footer {
        border-top: none;
    }

    .btn-warning {
        background-color: #ff9f43;
        border: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.querySelector('#modal-master');
        if (modal) {
            modal.classList.add('modal-shake');
        }
    });
</script>