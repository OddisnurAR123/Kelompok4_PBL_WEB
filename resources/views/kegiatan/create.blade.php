<form action="{{ url('/kegiatan/store') }}" method="POST" id="form-tambah-kegiatan">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Kegiatan</label>
                    <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control" maxlength="10" required>
                    <small id="error-kode_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" maxlength="100" required>
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode Kegiatan</label>
                    <input type="number" name="periode" id="periode" class="form-control" placeholder="Masukkan Tahun" length="4" required>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
                </div>                
                <div class="form-group">
                    <label>Kategori Kegiatan</label>
                    <select name="id_kategori_kegiatan" id="id_kategori_kegiatan" class="form-control" required>
                        <option value="">Pilih Kategori Kegiatan</option>
                        @foreach($kategoriKegiatan as $kategori)
                            <option value="{{ $kategori->id_kategori_kegiatan }}">{{ $kategori->nama_kategori_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <!-- Anggota Kegiatan -->
                <div id="anggota-section">
                    <div class="anggota-group d-flex justify-content-between mb-3">
                        <div class="col-5">
                            <label>Pengguna 1</label>
                            <select name="anggota[0][id_pengguna]" class="form-control" required>
                                <option value="">Pilih Pengguna</option>
                                @foreach($pengguna as $user)
                                    <option value="{{ $user->id_pengguna }}">{{ $user->nama_pengguna }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5 position-relative">
                            <label>Jabatan</label>
                            <select name="anggota[0][id_jabatan_kegiatan]" class="form-control" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatanKegiatan as $jabatan)
                                    <option value="{{ $jabatan->id_jabatan_kegiatan }}">{{ $jabatan->nama_jabatan_kegiatan }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="addAnggota" class="btn btn-light btn-sm position-absolute" style="top: -5px; right: 0; border: none; background: none;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="col-2"></div>
                    </div>
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
        // Menambahkan anggota baru
        let anggotaIndex = 1;
        $("#addAnggota").click(function() {
            let newAnggota = `
                <div class="anggota-group d-flex mb-3">
                    <div class="col-5">
                        <label>Pengguna ${anggotaIndex + 1}</label>
                        <select name="anggota[${anggotaIndex}][id_pengguna]" class="form-control" required>
                            <option value="">Pilih Pengguna</option>
                            @foreach($pengguna as $user)
                                <option value="{{ $user->id_pengguna }}">{{ $user->nama_pengguna }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <label>Jabatan</label>
                        <select name="anggota[${anggotaIndex}][id_jabatan_kegiatan]" class="form-control" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatanKegiatan as $jabatan)
                                <option value="{{ $jabatan->id_jabatan_kegiatan }}">{{ $jabatan->nama_jabatan_kegiatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            `;
            $('#anggota-section').append(newAnggota);
            anggotaIndex++;
        });

        // Validasi form menggunakan jQuery Validate
        $("#form-tambah-kegiatan").validate({
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat menyimpan data kegiatan. Coba lagi.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>