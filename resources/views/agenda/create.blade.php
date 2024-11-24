<form action="{{ url('/agenda/create') }}" method="POST" id="form-tambah-agenda">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Agenda</label>
                    <input type="text" name="kode_agenda" id="kode_agenda" class="form-control" required>
                    <small id="error-kode_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
                    <small id="error-nama_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($kegiatan as $k)
                            <option value="{{ $k->id_kegiatan }}">{{ $k->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tempat Agenda</label>
                    <input type="text" name="tempat_agenda" id="tempat_agenda" class="form-control" required>
                    <small id="error-tempat_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Pengguna</label>
                    <select name="id_jenis_pengguna" id="id_jenis_pengguna" class="form-control" required>
                        <option value="">-- Pilih Jenis Pengguna --</option>
                        @foreach($jenisPengguna as $jp)
                            <option value="{{ $jp->id_jenis_pengguna }}">{{ $jp->nama_jenis_pengguna }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jabatan Kegiatan</label>
                    <select name="id_jabatan_kegiatan" id="id_jabatan_kegiatan" class="form-control" required>
                        <option value="">-- Pilih Jabatan Kegiatan --</option>
                        @foreach($jabatanKegiatan as $jk)
                            <option value="{{ $jk->id }}">{{ $jk->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bobot Anggota</label>
                    <input type="number" name="bobot_anggota" id="bobot_anggota" class="form-control" required>
                    <small id="error-bobot_anggota" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Agenda</label>
                    <input type="date" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
                    <small id="error-tanggal_agenda" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
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
        $("#form-tambah-agenda").validate({
            rules: {
                kode_agenda: { required: true, minlength: 3 },
                nama_agenda: { required: true, minlength: 3 },
                id_kegiatan: { required: true },
                tempat_agenda: { required: true },
                id_jenis_pengguna: { required: true },
                id_jabatan_kegiatan: { required: true },
                bobot_anggota: { required: true, number: true },
                tanggal_agenda: { required: true, date: true }
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
            }
        });
    });
</script>
