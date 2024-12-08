<!-- Modal -->
<div id="modal-master" class="modal-dialog modal-lg modal-shake" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                @if(!$detailAgenda)
                    Kesalahan
                @else
                    Detail Agenda
                @endif
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if(!$detailAgenda)
                <div class="alert alert-danger text-center">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th style="width: 40%;">ID Detail Agenda:</th>
                        <td class="col-9">{{ $detailAgenda->id_detail_agenda }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Nama Kegiatan:</th>
                        <td class="col-9">{{ $detailAgenda->kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Nama Agenda:</th>
                        <td class="col-9">{{ $detailAgenda->agenda->nama_agenda }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Progres Agenda:</th>
                        <td class="col-9">{{ $detailAgenda->progres_agenda }}%</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Keterangan:</th>
                        <td class="col-9">{{ $detailAgenda->keterangan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Berkas:</th>
                        <td class="col-9">
                            @if($detailAgenda->berkas)
                                <a href="{{ asset('storage/'.Str::replaceFirst('storage/', '', $detailAgenda->berkas)) }}" target="_blank">Lihat Berkas</a>
                            @else
                                <span>Belum ada berkas yang diunggah.</span>
                            @endif
                        </td>
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