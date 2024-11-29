@empty($kategoriKegiatan)
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
<form action="{{ url('/kategori_kegiatan/' . $kategoriKegiatan->id_kategori_kegiatan . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Kategori Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Kode Kategori Kegiatan -->
                <div class="form-group">
                    <label>Kode Kategori Kegiatan</label>
                    <input value="{{ $kategoriKegiatan->kode_kategori_kegiatan }}" type="text" name="kode_kategori_kegiatan" id="kode_kategori_kegiatan" class="form-control" required>
                    <small id="error-kode_kategori_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <!-- Nama Kategori Kegiatan -->
                <div class="form-group">
                    <label>Nama Kategori Kegiatan</label>
                    <input value="{{ $kategoriKegiatan->nama_kategori_kegiatan }}" type="text" name="nama_kategori_kegiatan" id="nama_kategori_kegiatan" class="form-control" required>
                    <small id="error-nama_kategori_kegiatan" class="error-text form-text text-danger"></small>
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
            kode_kategori_kegiatan: { required: true, minlength: 3, maxlength: 10 }, // Sesuaikan dengan panjang karakter kode_kategori_kegiatan
            nama_kategori_kegiatan: { required: true, minlength: 3, maxlength: 50 }, // Sesuaikan dengan panjang karakter nama_kategori_kegiatan
        },
        messages: {
            kode_kategori_kegiatan: {
                required: "Kode Kategori Kegiatan harus diisi.",
                minlength: "Kode Kategori Kegiatan minimal 3 karakter.",
                maxlength: "Kode Kategori Kegiatan maksimal 10 karakter."
            },
            nama_kategori_kegiatan: {
                required: "Nama Kategori Kegiatan harus diisi.",
                minlength: "Nama Kategori Kegiatan minimal 3 karakter.",
                maxlength: "Nama Kategori Kegiatan maksimal 50 karakter."
            }
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
                        datajabatanKegiatan.ajax.reload();
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
