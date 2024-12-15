<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

<form action="{{ url('/detail_agenda/store') }}" method="POST" id="form-tambah-detail_agenda" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Progres Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="id_kegiatan">Kegiatan</label>
                    <input type="text" id="id_kegiatan" class="form-control" value="{{ $kegiatan->nama_kegiatan }}" readonly>
                    <input type="hidden" name="id_kegiatan" value="{{ $kegiatan->id_kegiatan }}">
                    <small id="error-id_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label for="id_agenda">Agenda</label>
                    <input type="text" id="id_agenda" class="form-control" value="{{ $agenda->nama_agenda }}" readonly>
                    <input type="hidden" name="id_agenda" value="{{ $agenda->id_agenda }}">
                    <small id="error-id_agenda" class="error-text form-text text-danger"></small>
                </div>                               
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" maxlength="100" required>
                    <small id="error-keterangan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Progres Agenda</label>
                    <input type="number" name="progres_agenda" id="progres_agenda" class="form-control" step="0.01" min="0" max="100" required>
                    <small id="error-progres_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Berkas</label>
                    <input type="file" name="berkas" id="berkas" class="form-control" required>
                    <small id="error-berkas" class="error-text form-text text-danger"></small>
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
    $("#form-tambah-detail_agenda").on("submit", function (e) {
        e.preventDefault();
        let form = $(this);
        let actionUrl = form.attr("action");
        let formData = new FormData(this);

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
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
            error: function(xhr) {
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