<form action="{{ url('/detail_agenda/update/'.$detailAgenda->id_detail_agenda) }}" method="POST" id="form-edit-detail_agenda" enctype="multipart/form-data">
    @csrf
    @method('PUT') <!-- Untuk mengubah data dengan method PUT -->
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Detail Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="id_kegiatan">Kegiatan</label>
                    <input type="text" id="id_kegiatan" class="form-control" value="{{ $detailAgenda->kegiatan->nama_kegiatan }}" readonly>
                    <small id="error-id_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="id_agenda">Agenda</label>
                    <input type="text" id="id_agenda" class="form-control" value="{{ $detailAgenda->agenda->nama_agenda }}" readonly>
                    <small id="error-id_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" maxlength="100" value="{{ old('keterangan', $detailAgenda->keterangan) }}" required>
                    <small id="error-keterangan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Progres Agenda</label>
                    <input type="number" name="progres_agenda" id="progres_agenda" class="form-control" step="0.01" min="0" max="100" value="{{ old('progres_agenda', $detailAgenda->progres_agenda) }}" required>
                    <small id="error-progres_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Berkas</label>
                    <input type="file" name="berkas" id="berkas" class="form-control">
                    <small id="error-berkas" class="error-text form-text text-danger"></small>
                    @if ($detailAgenda->berkas)
                        <div class="mt-2">
                            <strong>File Saat Ini:</strong>
                            <a href="{{ asset('storage/'.Str::replaceFirst('storage/', '', $detailAgenda->berkas)) }}" target="_blank">Lihat Berkas</a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
    $("#form-edit-detail_agenda").on("submit", function (e) {
        e.preventDefault();
        let form = $(this);
        let actionUrl = form.attr("action");

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                    $('#modal-master').modal('hide');
                    if (typeof dataDetailAgenda !== 'undefined') {
                        dataDetailAgenda.ajax.reload();
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: response.message
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan pada server, silakan coba lagi.'
                });
            }
        });
    });
});
</script>