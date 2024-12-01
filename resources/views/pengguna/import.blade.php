<form action="{{ url('/pengguna/import') }}" method="POST" id="form-import-pengguna" enctype="multipart/form-data">
    @csrf
    <div id="modal-user" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template_pengguna.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i>Download
                    </a>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_pengguna" id="file_pengguna" class="form-control" required>
                    <small id="error-file_pengguna" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-import-pengguna").validate({
            rules: {
                file_pengguna: {
                    required: true,
                    extension: "xlsx"
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
    url: '/pengguna/import',  // Pastikan form.action adalah URL yang benar (POST)
    type: 'POST',      // Gunakan metode POST
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        if (response.status) {
            $('#modal-user').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message
            });
            tablePengguna.ajax.reload();
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
    }
});

                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>