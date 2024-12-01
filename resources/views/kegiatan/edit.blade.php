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
                <div class="form-group">
                    <label>Kode Kegiatan</label>
                    <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control" maxlength="10" value="{{ old('kode_kegiatan', $kegiatan->kode_kegiatan) }}">
                    <small id="error-kode_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" maxlength="100" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required>
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('Y-m-d\TH:i')) }}" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('Y-m-d\TH:i')) }}">
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
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

                <!-- Anggota Kegiatan -->
                <div id="anggota-section">
                    @foreach($kegiatan->pengguna as $index => $anggota)
                        <div class="anggota-group d-flex justify-content-between mb-3" id="anggota-group-{{ $index }}">
                            <div class="col-5">
                                <label>Pengguna {{ $index + 1 }}</label>
                                <select name="anggota[{{ $index }}][id_pengguna]" class="form-control" required>
                                    <option value="">Pilih Pengguna</option>
                                    @foreach($pengguna as $user)
                                        <option value="{{ $user->id_pengguna }}" {{ $anggota->id_pengguna == $user->id_pengguna ? 'selected' : '' }}>{{ $user->nama_pengguna }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5 position-relative">
                                <label>Jabatan</label>
                                <select name="anggota[{{ $index }}][id_jabatan_kegiatan]" class="form-control" required>
                                    <option value="">Pilih Jabatan</option>
                                    @foreach($jabatanKegiatan as $jabatan)
                                        <option value="{{ $jabatan->id_jabatan_kegiatan }}" {{ $anggota->id_jabatan_kegiatan == $jabatan->id_jabatan_kegiatan ? 'selected' : '' }}>{{ $jabatan->nama_jabatan_kegiatan }}</option>
                                    @endforeach
                                </select>
                                @if($index > 0)
                                    <button type="button" class="btn btn-danger btn-sm position-absolute remove-anggot" style="top: -5px; right: 0; border: none; background: none;" data-index="{{ $index }}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="col-2"></div>
                        </div>
                    @endforeach
                
                    <!-- Tombol untuk menambah anggota baru -->
                    <div class="anggota-group d-flex justify-content-between mb-3" id="anggota-group-{{ count($kegiatan->pengguna) }}">
                        <div class="col-5">
                            <label>Pengguna {{ count($kegiatan->pengguna) + 1 }}</label>
                            <select name="anggota[{{ count($kegiatan->pengguna) }}][id_pengguna]" class="form-control" required>
                                <option value="">Pilih Pengguna</option>
                                @foreach($pengguna as $user)
                                    <option value="{{ $user->id_pengguna }}">{{ $user->nama_pengguna }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5 position-relative">
                            <label>Jabatan</label>
                            <select name="anggota[{{ count($kegiatan->pengguna) }}][id_jabatan_kegiatan]" class="form-control" required>
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
                
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
    // Validasi dan submit form
    $("#form-edit-kegiatan").validate({
        rules: {
            kode_kegiatan: {
                required: true,
                minlength: 3
            },
            nama_kegiatan: {
                required: true,
                minlength: 3
            },
            tanggal_mulai: {
                required: true
            },
            tanggal_selesai: {
                required: true
            },
            id_kategori_kegiatan: {
                required: true
            }
            // Menambahkan aturan untuk anggota agar anggota baru yang belum dipilih tidak wajib
        'anggota[][id_pengguna]': {
            required: function(element) {
                // Cek jika anggota baru kosong, jika iya, tidak wajib
                return $(element).val() !== '';
            }
        },
        'anggota[][id_jabatan_kegiatan]': {
            required: function(element) {
                return $(element).val() !== '';
            }
        }
        },
        submitHandler: function(form) {
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
                        });
                    } else {
                        $('.error-text').text(''); // Menghapus pesan error sebelumnya
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]); // Menampilkan pesan error untuk masing-masing field
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Tidak dapat memperbarui data kegiatan. Coba lagi.'
                    });
                }
            });
            return false; // Mencegah form dikirim secara default
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass('is-valid').removeClass('is-invalid');
        }
    });

    $('#addAnggota').click(function() {
    var lastIndex = $('#anggota-section .anggota-group').length;
    var newIndex = lastIndex;
    var newAnggotaGroup = `
        <div class="anggota-group d-flex justify-content-between mb-3" id="anggota-group-${newIndex}">
            <div class="col-5">
                <label>Pengguna ${newIndex + 1}</label>
                <select name="anggota[${newIndex}][id_pengguna]" class="form-control" required>
                    <option value="">Pilih Pengguna</option>
                    @foreach($pengguna as $user)
                        <option value="{{ $user->id_pengguna }}">{{ $user->nama_pengguna }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-5 position-relative">
                <label>Jabatan</label>
                <select name="anggota[${newIndex}][id_jabatan_kegiatan]" class="form-control" required>
                    <option value="">Pilih Jabatan</option>
                    @foreach($jabatanKegiatan as $jabatan)
                        <option value="{{ $jabatan->id_jabatan_kegiatan }}">{{ $jabatan->nama_jabatan_kegiatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2"></div>
        </div>
    `;
    $('#anggota-section').append(newAnggotaGroup);
});
    // Menghapus anggota
    $(document).on('click', '.remove-anggot', function() {
        var index = $(this).data('index');
        $(`#anggota-group-${index}`).remove();
    });
});
</script>