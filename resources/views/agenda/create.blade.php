<form action="{{ route('agenda.store') }}" method="POST" id="form-create-agenda" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Agenda</label>
                    <input type="text" name="kode_agenda" id="kode_agenda" class="form-control" required>
                    <small id="error-kode_agenda" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
                    <small id="error-nama_agenda" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">Pilih Kegiatan</option>
                        @foreach($kegiatan as $item)
                            <option value="{{ $item->id_kegiatan }}">{{ $item->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tempat Agenda</label>
                    <input type="text" name="tempat_agenda" id="tempat_agenda" class="form-control" required>
                    <small id="error-tempat_agenda" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Pengguna</label>
                    <select name="id_jenis_pengguna" id="id_jenis_pengguna" class="form-control" required>
                        <option value="">Pilih Jenis Pengguna</option>
                        @foreach($jenisPengguna as $item)
                            <option value="{{ $item->id_jenis_pengguna }}">{{ $item->nama_jenis_pengguna }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_pengguna" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jabatan Kegiatan</label>
                    <select name="id_jabatan_kegiatan" id="id_jabatan_kegiatan" class="form-control">
                        <option value="">Pilih Jabatan Kegiatan</option>
                        @foreach($jabatanKegiatan as $item)
                            <option value="{{ $item->id_jabatan_kegiatan }}">{{ $item->nama_jabatan_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jabatan_kegiatan" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bobot Anggota</label>
                    <input type="number" name="bobot_anggota" id="bobot_anggota" class="form-control" required>
                    <small id="error-bobot_anggota" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Agenda</label>
                    <input type="date" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
                    <small id="error-tanggal_agenda" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    <small id="error-deskripsi" class="text-danger"></small>
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
        $("#form-create-agenda").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire('Berhasil', response.message, 'success');
                            $('#modal-master').modal('hide');
                            // Reload data jika menggunakan DataTables
                            dataAgenda.ajax.reload();
                        } else {
                            $.each(response.msgField, function(key, value) {
                                $('#error-' + key).text(value[0]);
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                });
                return false;
            }
        });
    });
</script>
