<form id="form-tambah-agenda" action="{{ url('/agenda/create_ajax') }}" method="POST">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Agenda Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Pilih Kegiatan -->
                <div class="form-group">
                    <label for="id_kegiatan">Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">- Pilih Kegiatan -</option>
                        @foreach($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->id_kegiatan }}">{{ $kegiatan->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="form-text text-danger"></small>
                </div>
                
                <!-- Kode Agenda -->
                <div class="form-group">
                    <label for="kode_agenda">Kode Agenda</label>
                    <input type="text" name="kode_agenda" id="kode_agenda" class="form-control" required>
                    <small id="error-kode_agenda" class="form-text text-danger"></small>
                </div>

                <!-- Nama Agenda -->
                <div class="form-group">
                    <label for="nama_agenda">Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
                    <small id="error-nama_agenda" class="form-text text-danger"></small>
                </div>

                <!-- Tempat Agenda -->
                <div class="form-group">
                    <label for="tempat_agenda">Tempat Agenda</label>
                    <input type="text" name="tempat_agenda" id="tempat_agenda" class="form-control" required>
                    <small id="error-tempat_agenda" class="form-text text-danger"></small>
                </div>

                <!-- Jenis Pengguna -->
                <div class="form-group">
                    <label for="id_jenis_pengguna">Jenis Pengguna</label>
                    <select name="id_jenis_pengguna" id="id_jenis_pengguna" class="form-control" required>
                        <option value="">- Pilih Jenis Pengguna -</option>
                        @foreach($jenisPenggunas as $jenisPengguna)
                            <option value="{{ $jenisPengguna->id_jenis_pengguna }}">{{ $jenisPengguna->nama_jenis_pengguna }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_pengguna" class="form-text text-danger"></small>
                </div>

                <!-- Jabatan Kegiatan -->
                <div class="form-group">
                    <label for="id_jabatan_kegiatan">Jabatan Kegiatan</label>
                    <select name="id_jabatan_kegiatan" id="id_jabatan_kegiatan" class="form-control" required>
                        <option value="">- Pilih Jabatan Kegiatan -</option>
                        @foreach($jabatanKegiatans as $jabatanKegiatan)
                            <option value="{{ $jabatanKegiatan->id }}">{{ $jabatanKegiatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jabatan_kegiatan" class="form-text text-danger"></small>
                </div>

                <!-- Bobot Anggota -->
                <div class="form-group">
                    <label for="bobot_anggota">Bobot Anggota (opsional)</label>
                    <input type="number" name="bobot_anggota" id="bobot_anggota" class="form-control">
                    <small id="error-bobot_anggota" class="form-text text-danger"></small>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="deskripsi">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    <small id="error-deskripsi" class="form-text text-danger"></small>
                </div>

                <!-- Tanggal Agenda -->
                <div class="form-group">
                    <label for="tanggal_agenda">Tanggal Agenda</label>
                    <input type="date" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
                    <small id="error-tanggal_agenda" class="form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Validasi dan submit dengan AJAX
        $("#form-tambah-agenda").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr("action"),
                method: $(this).attr("method"),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        // Reload data table atau halaman
                        dataTable.ajax.reload();
                    } else {
                        // Tampilkan error validasi
                        $('.form-text.text-danger').text('');
                        $.each(response.errors, function(key, value) {
                            $('#error-' + key).text(value[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menyimpan data agenda!'
                    });
                }
            });
        });
    });
</script>
