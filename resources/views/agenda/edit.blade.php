@empty($agenda)
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
<form action="{{ url('/agenda/' . $agenda->id_agenda . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Kode Agenda -->
                <div class="form-group">
                    <label>Kode Agenda</label>
                    <input value="{{ $agenda->kode_agenda }}" type="text" name="kode_agenda" id="kode_agenda" class="form-control" required>
                    <small id="error-kode_agenda" class="error-text form-text text-danger"></small>
                </div>
                <!-- Nama Agenda -->
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input value="{{ $agenda->nama_agenda }}" type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
                    <small id="error-nama_agenda" class="error-text form-text text-danger"></small>
                </div>
                <!-- ID Kegiatan -->
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">Pilih Kegiatan</option>
                        @foreach($kegiatan as $item)
                            <option value="{{ $item->id_kegiatan }}" {{ $agenda->id_kegiatan == $item->id_kegiatan ? 'selected' : '' }}>
                                {{ $item->nama_kegiatan }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <!-- Tempat Agenda -->
                <div class="form-group">
                    <label>Tempat Agenda</label>
                    <input value="{{ $agenda->tempat_agenda }}" type="text" name="tempat_agenda" id="tempat_agenda" class="form-control" required>
                    <small id="error-tempat_agenda" class="error-text form-text text-danger"></small>
                </div>
                <!-- ID Jenis Pengguna -->
                <div class="form-group">
                    <label>Jenis Pengguna</label>
                    <select name="id_jenis_pengguna" id="id_jenis_pengguna" class="form-control" required>
                        <option value="">Pilih Jenis Pengguna</option>
                        @foreach($jenisPengguna as $jenis)
                            <option value="{{ $jenis->id_jenis_pengguna }}" {{ $agenda->id_jenis_pengguna == $jenis->id_jenis_pengguna ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis_pengguna }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <!-- ID Jabatan Kegiatan -->
                <div class="form-group">
                    <label>Jabatan Kegiatan</label>
                    <select name="id_jabatan_kegiatan" id="id_jabatan_kegiatan" class="form-control">
                        <option value="">Pilih Jabatan (Opsional)</option>
                        @foreach($jabatanKegiatan as $jabatan)
                            <option value="{{ $jabatan->id_jabatan_kegiatan }}" {{ $agenda->id_jabatan_kegiatan == $jabatan->id_jabatan_kegiatan ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan_kegiatan }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <!-- Bobot Anggota -->
                <div class="form-group">
                    <label>Bobot Anggota</label>
                    <input value="{{ $agenda->bobot_anggota }}" type="number" name="bobot_anggota" id="bobot_anggota" class="form-control" required>
                    <small id="error-bobot_anggota" class="error-text form-text text-danger"></small>
                </div>
                <!-- Tanggal Agenda -->
                <div class="form-group">
                    <label>Tanggal Agenda</label>
                    <input value="{{ $agenda->tanggal_agenda }}" type="date" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
                    <small id="error-tanggal_agenda" class="error-text form-text text-danger"></small>
                </div>
                <!-- Deskripsi -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" required>{{ $agenda->deskripsi }}</textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
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
            kode_agenda: { required: true, minlength: 3, maxlength: 10 },
            nama_agenda: { required: true, minlength: 3, maxlength: 50 },
            id_kegiatan: { required: true },
            tempat_agenda: { required: true, minlength: 3 },
            id_jenis_pengguna: { required: true },
            bobot_anggota: { required: true, number: true },
            tanggal_agenda: { required: true, date: true },
            deskripsi: { required: true, minlength: 5 },
        },
        messages: {
            // Customize error messages for each field
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
                        dataAgenda.ajax.reload();
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
