@empty($pengguna)
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
                Data yang Anda cari tidak ditemukan
            </div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/pengguna/' . $pengguna->id_pengguna . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Pengguna -->
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input value="{{ $pengguna->nama_pengguna }}" type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" required>
                    <small id="error-nama_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <!-- Username -->
                <div class="form-group">
                    <label>Username</label>
                    <input value="{{ $pengguna->username }}" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <!-- Email -->
                <div class="form-group">
                    <label>Email</label>
                    <input value="{{ $pengguna->email }}" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <!-- Jenis Pengguna -->
                <div class="form-group">
                    <label>Jenis Pengguna</label>
                    <select name="id_jenis_pengguna" id="id_jenis_pengguna" class="form-control" required>
                        <option value="">Pilih Jenis Pengguna</option>
                        @foreach($jenisPengguna as $jenis)
                            <option value="{{ $jenis->id_jenis_pengguna }}" {{ $pengguna->id_jenis_pengguna == $jenis->id_jenis_pengguna ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis_pengguna }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <!-- Foto Profil -->
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto_profil" id="foto_profil" class="form-control">
                    <small id="error-foto_profil" class="error-text form-text text-danger"></small>
                    @if($pengguna->foto_profil)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $pengguna->foto_profil) }}" alt="Foto Profil" class="img-thumbnail" style="width: 100px; height: 100px;">
                        </div>
                    @endif
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
    $("#form-edit").validate({
        rules: {
            nama_pengguna: { required: true, minlength: 3, maxlength: 50 },
            username: { required: true, minlength: 3, maxlength: 20 },
            email: { required: true, email: true },
            id_jenis_pengguna: { required: true },
        },
        messages: {
            nama_pengguna: {
                required: "Nama Pengguna harus diisi.",
                minlength: "Nama Pengguna minimal 3 karakter.",
                maxlength: "Nama Pengguna maksimal 50 karakter."
            },
            username: {
                required: "Username harus diisi.",
                minlength: "Username minimal 3 karakter.",
                maxlength: "Username maksimal 20 karakter."
            },
            email: {
                required: "Email harus diisi.",
                email: "Format email tidak valid."
            },
            id_jenis_pengguna: {
                required: "Jenis Pengguna harus dipilih."
            },
        },
            submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataPengguna.ajax.reload();
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
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
