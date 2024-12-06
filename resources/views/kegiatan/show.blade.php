<!-- Modal -->
<div id="modal-master" class="modal-dialog modal-lg modal-shake" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                @if(!$kegiatan)
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
            @if(!$kegiatan)
                <div class="alert alert-danger text-center">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th style="width: 40%;">ID Kegiatan:</th>
                        <td class="col-9">{{ $kegiatan->id_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Kode Kegiatan:</th>
                        <td class="col-9">{{ $kegiatan->kode_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Nama Kegiatan:</th>
                        <td class="col-9">{{ $kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Tanggal Mulai:</th>
                        <td class="col-9">{{ $kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Tanggal Selesai:</th>
                        <td class="col-9">{{ $kegiatan->tanggal_selesai ? \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Kategori Kegiatan:</th>
                        <td class="col-9">{{ $kegiatan->kategoriKegiatan->nama_kategori_kegiatan ?? 'Tidak ada kategori' }}</td>
                    </tr>
                </table>

                <h4 class="mt-4">Anggota Kegiatan</h4>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan->anggota as $anggota)
                            <tr>
                                <td>{{ $anggota->nama_pengguna }}</td>
                                <td>{{ $anggota->nama_jabatan_kegiatan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Tidak ada anggota kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
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