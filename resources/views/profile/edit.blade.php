@empty($profile)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
            </div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/profile.update/' . $profile->id_pengguna . '/update') }}" method="POST" id="form-edit-profile">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama -->
                <div class="form-group">
                    <label>Nama</label>
                    <input value="{{ $user->nama_pengguna }}" type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" required>
                    <small id="error-nama_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <!-- Username -->
                <div class="form-group">
                    <label>Username</label>
                    <input value="{{ $user->username }}" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <!-- Avatar -->
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto_profil" id="foto_profil" class="form-control">
                    <small id="error-foto_profil" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-edit-profile").validate({
        rules: {
            nama_pengguna: { required: true, minlength: 3, maxlength: 50 },
            username: { required: true, minlength: 3, maxlength: 20 },
            foto_profil: { extension: "jpg|jpeg|png", filesize: 2 * 1024 * 1024 }, // Maksimal 2MB
        },
        messages: {
            nama_pengguna: {
                required: "Nama tidak boleh kosong.",
                minlength: "Nama minimal 3 karakter.",
                maxlength: "Nama maksimal 50 karakter."
            },
            username: {
                required: "Username tidak boleh kosong.",
                minlength: "Username minimal 3 karakter.",
                maxlength: "Username maksimal 20 karakter."
            },
            foto_profil: {
                extension: "Format file harus JPG, JPEG, atau PNG.",
                filesize: "Ukuran file maksimal 2MB."
            },
        },
        submitHandler: function(form) {
            let formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            location.reload(); // Reload halaman untuk memperbarui data
                        });
                    } else {
                        $('.error-text').text('');
                        $.each(response.errors, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Silakan coba lagi.'
                    });
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
@endempty