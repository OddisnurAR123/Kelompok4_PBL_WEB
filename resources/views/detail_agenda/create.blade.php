<form action="{{ url('/detail_agenda/store') }}" method="POST" id="form-tambah-detail_kegiatan" enctype="multipart/form-data">
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
                    <label for="id_kegiatan">Pilih Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">Pilih Kegiatan</option>
                        @foreach ($kegiatan as $kegiatan)
                            <option value="{{ $kegiatan->id_kegiatan }}">{{ $kegiatan->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="id_agenda">Pilih Agenda</label>
                    <select name="id_agenda" id="id_agenda" class="form-control" required>
                        <option value="">Pilih Agenda</option>
                    </select>
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
        const agendaData = @json($agenda);

        $("#id_kegiatan").on("change", function () {
            let id_kegiatan = $(this).val();
            let agendaDropdown = $("#id_agenda");

            agendaDropdown.empty().append('<option value="">Pilih Agenda</option>');

            if (id_kegiatan && agendaData[id_kegiatan]) {
                agendaData[id_kegiatan].forEach(function (agenda) {
                    agendaDropdown.append(
                        `<option value="${agenda.id_agenda}">${agenda.nama_agenda}</option>`
                    );
                });
            } else {
                agendaDropdown.append('<option value="">Tidak ada agenda</option>');
            }
        });

        // Validasi Form
        $("#form-tambah-detail_kegiatan").validate({
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
                            text: 'Tidak dapat menyimpan data detail kegiatan. Coba lagi.'
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
