<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ url('/pengguna/create') }}" method="POST" id="form-create-pengguna">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" required>
                    <small id="error-nama_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Pengguna</label>
                    <select name="id_jenis_pengguna" id="id_jenis_pengguna" class="form-control" required>
                        <option value="">-- Pilih Jenis Pengguna --</option>
                        @foreach($jenis_pengguna as $jenis)
                            <option value="{{ $jenis->id_jenis_pengguna }}">{{ $jenis->nama_jenis_pengguna }}</option>
                        @endforeach
                    </select>
                    <small id="error-jenis_pengguna_id" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#form-create-pengguna").validate({
        rules: {
            nama_pengguna: { required: true, minlength: 3 },
            username: { required: true, minlength: 3 },
            email: { required: true, email: true },
            jenis_pengguna_id: { required: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        $('#table_pengguna').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Ada masalah pada server. Silakan coba lagi nanti.'
                    });
                }
            });
            return false;
        }
    });
});
</script>
