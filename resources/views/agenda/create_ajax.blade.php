<form action="{{ url('agenda/create_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Agenda</h5>
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
                        <option value="">- Pilih Kegiatan -</option>
                        @foreach($kegiatan as $item)
                            <option value="{{ $item->id_kegiatan }}">{{ $item->nama_kegiatan }}</option>
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
                        <option value="">- Pilih Jenis Pengguna -</option>
                        @foreach($jenisPengguna as $item)
                            <option value="{{ $item->id_jenis_pengguna }}">{{ $item->nama_jenis_pengguna }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jabatan Kegiatan</label>
                    <select name="id_jabatan_kegiatan" id="id_jabatan_kegiatan" class="form-control" required>
                        <option value="">- Pilih Jabatan Kegiatan -</option>
                        @foreach($jabatanKegiatan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bobot Anggota</label>
                    <input type="number" name="bobot_anggota" id="bobot_anggota" class="form-control">
                    <small id="error-bobot_anggota" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Agenda</label>
                    <input type="datetime-local" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
                    <small id="error-tanggal_agenda" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
   $(document).ready(function() {
    // Load form tambah agenda secara AJAX
    $('#btn-create-agenda').on('click', function() {
        $.ajax({
            url: "{{ route('create_ajax') }}",
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    $('#modal-body').html(response.html); // Isi modal dengan form
                    $('#modal-master').modal('show'); // Tampilkan modal
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Gagal memuat form tambah agenda.', 'error');
            }
        });
    });

    // Proses simpan data agenda
    $(document).on('submit', '#form-create-agenda', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#modal-master').modal('hide'); // Tutup modal
                    Swal.fire('Berhasil', response.message, 'success');
                    // Reload data tabel jika ada
                    if (typeof dataAgenda !== 'undefined') {
                        dataAgenda.ajax.reload();
                    }
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        });
    });
});
`