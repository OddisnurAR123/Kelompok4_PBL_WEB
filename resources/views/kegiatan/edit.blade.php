<form action="{{ url('/kegiatan/update/'.$kegiatan->id_kegiatan) }}" method="POST" id="form-edit-kegiatan">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Kode Kegiatan -->
                <div class="form-group">
                    <label>Kode Kegiatan</label>
                    <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control" maxlength="10" value="{{ old('kode_kegiatan', $kegiatan->kode_kegiatan) }}">
                    <small id="error-kode_kegiatan" class="error-text form-text text-danger"></small>
                </div>

                <!-- Nama Kegiatan -->
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" maxlength="100" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required>
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>

                <!-- Tanggal Mulai -->
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('Y-m-d\TH:i')) }}" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>

                <!-- Tanggal Selesai -->
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('Y-m-d\TH:i')) }}">
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>

                <!-- Periode Kegiatan -->
                <div class="form-group">
                    <label>Periode Kegiatan</label>
                    <input type="number" name="periode" id="periode" class="form-control" maxlength="4" value="{{ old('periode', $kegiatan->periode) }}" required>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
                </div>

                <!-- Kategori Kegiatan -->
                <div class="form-group">
                    <label>Kategori Kegiatan</label>
                    <select name="id_kategori_kegiatan" id="id_kategori_kegiatan" class="form-control">
                        <option value="">Pilih Kategori Kegiatan</option>
                        @foreach($kategoriKegiatan as $kategori)
                            <option value="{{ $kategori->id_kategori_kegiatan }}" {{ $kegiatan->id_kategori_kegiatan == $kategori->id_kategori_kegiatan ? 'selected' : '' }}>{{ $kategori->nama_kategori_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_kegiatan" class="error-text form-text text-danger"></small>
                </div>

                <!-- Tempat Kegiatan -->
                <div class="form-group">
                    <label>Tempat Kegiatan</label>
                    <input type="text" name="tempat_kegiatan" id="tempat_kegiatan" class="form-control" maxlength="100" value="{{ old('tempat_kegiatan', $kegiatan->tempat_kegiatan) }}" required>
                    <small id="error-tempat_kegiatan" class="error-text form-text text-danger"></small>
                </div>

                <!-- Tombol Tambah Anggota -->
                <button type="button" id="addAnggota" class="btn p-0 border-0 bg-transparent mt-3" title="Tambah Anggota">
                    <i class="fas fa-plus text-primary"></i>
                </button>

                <!-- Anggota Kegiatan -->
                <div id="anggota-section">
                    @foreach($kegiatan->anggota as $index => $anggota)
                        <div class="anggota-group d-flex justify-content-between mb-3" id="anggota-group-{{ $index }}">
                            <!-- Kolom Pengguna -->
                            <div class="col-md-5">
                                <label>Pengguna {{ $index + 1 }}</label>
                                <select name="anggota[{{ $index }}][id_pengguna]" class="form-control" required>
                                    <option value="">Pilih Pengguna</option>
                                    @foreach($pengguna as $user)
                                        <option value="{{ $user->id_pengguna }}" {{ $anggota->id_pengguna == $user->id_pengguna ? 'selected' : '' }}>{{ $user->nama_pengguna }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kolom Jabatan -->
                            <div class="col-md-5 position-relative">
                                <label>Jabatan</label>
                                <select name="anggota[{{ $index }}][id_jabatan_kegiatan]" class="form-control" required>
                                    <option value="">Pilih Jabatan</option>
                                    @foreach($jabatanKegiatan as $jabatan)
                                        <option value="{{ $jabatan->id_jabatan_kegiatan }}" {{ $anggota->id_jabatan_kegiatan == $jabatan->id_jabatan_kegiatan ? 'selected' : '' }}>{{ $jabatan->nama_jabatan_kegiatan }}</option>
                                    @endforeach
                                </select>
                                @if($index > 0)
                                    <button type="button" class="btn btn-danger btn-sm position-absolute remove-anggota" style="top: -5px; right: 0;" data-index="{{ $index }}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
    $(document).ready(function () {
        $('#addAnggota').click(function () {
            var lastIndex = $('#anggota-section .anggota-group').length;
            var newAnggotaGroup = `
                <div class="anggota-group d-flex justify-content-between mb-3" id="anggota-group-${lastIndex}">
                    <div class="col-md-5">
                        <label>Pengguna ${lastIndex + 1}</label>
                        <select name="anggota[${lastIndex}][id_pengguna]" class="form-control" required>
                            <option value="">Pilih Pengguna</option>
                            @foreach($pengguna as $user)
                                <option value="{{ $user->id_pengguna }}">{{ $user->nama_pengguna }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5 position-relative">
                        <label>Jabatan</label>
                        <select name="anggota[${lastIndex}][id_jabatan_kegiatan]" class="form-control" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatanKegiatan as $jabatan)
                                <option value="{{ $jabatan->id_jabatan_kegiatan }}">{{ $jabatan->nama_jabatan_kegiatan }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger btn-sm position-absolute remove-anggota" style="top: -5px; right: 0;" data-index="${lastIndex}">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#anggota-section').append(newAnggotaGroup);
        });

        $(document).on('click', '.remove-anggota', function () {
            $(this).closest('.anggota-group').remove();
        });

        // Kirim form menggunakan AJAX
        $('#form-edit-kegiatan').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.status) {
                        Swal.fire('Success', response.message, 'success').then(() => {
                    $('#modalEditKegiatan').modal('hide');
                    location.reload();
                });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            });
        });
    });
</script>