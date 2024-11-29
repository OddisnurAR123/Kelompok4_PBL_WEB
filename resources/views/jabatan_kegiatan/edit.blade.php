@empty($jabatanKegiatan)
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
<form action="{{ url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Jabatan Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Kode Jabatan Kegiatan -->
                <div class="form-group">
                    <label>Kode Jabatan Kegiatan</label>
                    <input value="{{ $jabatanKegiatan->kode_jabatan_kegiatan }}" type="text" name="kode_jabatan_kegiatan" id="kode_jabatan_kegiatan" class="form-control" required>
                    <small id="error-kode_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <!-- Nama Jabatan Kegiatan -->
                <div class="form-group">
                    <label>Nama Jabatan Kegiatan</label>
                    <input value="{{ $jabatanKegiatan->nama_jabatan_kegiatan }}" type="text" name="nama_jabatan_kegiatan" id="nama_jabatan_kegiatan" class="form-control" required>
                    <small id="error-nama_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <!-- Is PIC -->
                <div class="form-group">
                    <label>Is PIC</label>
                    <select name="is_pic" id="is_pic" class="form-control" required>
                        <option value="1" {{ $jabatanKegiatan->is_pic == 1 ? 'selected' : '' }}>1 -Ya-</option>
                        <option value="0" {{ $jabatanKegiatan->is_pic == 0 ? 'selected' : '' }}>0 -Tidak-</option>
                    </select>
                    <small id="error-is_pic" class="error-text form-text text-danger"></small>
                </div>
                <!-- Urutan -->
                <div class="form-group">
                    <label>Urutan</label>
                    <input value="{{ $jabatanKegiatan->urutan }}" type="number" name="urutan" id="urutan" class="form-control" required>
                    <small id="error-urutan" class="error-text form-text text-danger"></small>
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
            kode_jabatan_kegiatan: { required: true, minlength: 3, maxlength: 20 },
            nama_jabatan_kegiatan: { required: true, minlength: 3, maxlength: 100 },
            is_pic: { required: true },
            urutan: { required: true, number: true, min: 1 }
        },
        messages: {
            is_pic: { required: "Pilih apakah ini merupakan PIC." },
            urutan: { 
                required: "Urutan tidak boleh kosong.", 
                number: "Urutan harus berupa angka.", 
                min: "Urutan tidak boleh kurang dari 1." 
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
