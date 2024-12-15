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
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <!-- Menampilkan Nama Kegiatan -->
                    <tr>
                        <th>Nama Kegiatan</th>
                        <td>{{ $agenda->kegiatan->nama_kegiatan }}</td>
                    </tr>

                    <!-- Menampilkan File Surat Tugas -->
                    <tr>
                        <th>File Surat Tugas</th>
                        <td>
                            @if($agenda->file_surat_tugas)
                                <a href="{{ asset('storage/' . $agenda->file_surat_tugas) }}" target="_blank" class="btn btn-primary">
                                    Lihat File Surat Tugas
                                </a>
                            @else
                                <span>Tidak ada file yang tersedia.</span>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.querySelector('#modal-master');
        if (modal) {
            modal.classList.add('modal-shake');
        }
    });
</script>
