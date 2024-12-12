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
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg modal-shake" role="document">
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
                        <th class="text-right col-3">Nama Pengguna:</th>
                        <td class="col-9">{{ $agenda->kegiatanUser->pengguna->nama_pengguna ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jabatan Pengguna:</th>
                        <td class="col-9">{{ $agenda->kegiatanUser->jabatanKegiatan->nama_jabatan_kegiatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bobot Anggota:</th>
                        <td class="col-9">{{ $agenda->bobot_anggota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Agenda:</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi:</th>
                        <td class="col-9">{{ $agenda->deskripsi ?? 'Tidak Ada Deskripsi' }}</td>
                    </tr>
                </table>

                <!-- Detail Agenda -->
                @if(!$detailAgenda)
                    <div class="alert alert-danger text-center">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                        Data yang Anda cari tidak ditemukan.
                    </div>
                @else
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Progres Agenda</h5>
                    </button>
                </div>
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
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.querySelector('#modal-master');
        if (modal) {
            modal.classList.add('modal-shake');
        }
    });
</script>