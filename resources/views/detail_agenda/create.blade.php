<form action="{{ url('/detail_agenda/store') }}" method="POST" id="form-tambah-detail_agenda" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Detail Agenda</h5>
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
        $("#form-tambah-detail_agenda").validate({
            rules: {
                id_kegiatan: {
                    required: true
                },
                id_agenda: {
                    required: true
                },
                keterangan: {
                    required: true,
                    minlength: 3
                },
                progres_agenda: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                },
                berkas: {
                    required: true,
                    accept: "application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,text/plain"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat menyimpan detail agenda. Coba lagi.'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            }
        });
    });
</script>