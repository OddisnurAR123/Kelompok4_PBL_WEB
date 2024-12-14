<div id="modal-unduh-surat-tugas" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Unduh Surat Tugas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            @if ($kegiatan->file_surat_tugas)
                <p><strong>File Tugas:</strong></p>
                <a href="{{ route('kegiatan.download', ['id' => $kegiatan->id_kegiatan]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> Unduh Surat Tugas
                </a>
            @else
                <p class="text-danger mt-3">
                    Surat Tugas belum diunggah.
                </p>
            @endif
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    function bukaModalUnduhSurat(id_kegiatan) {
    $.ajax({
        url: "{{ route('kegiatan.download', ['id' : '']) }}/" + id_kegiatan,
        type: "GET",
        success: function(response) {
            $('#modal-unduh-surat-tugas').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'File tidak ditemukan!'
            });
        }
    });
}
</script>