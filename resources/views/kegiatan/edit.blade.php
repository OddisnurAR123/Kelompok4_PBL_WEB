<form action="{{ url('/kegiatan/update/'.$kegiatan->id_kegiatan) }}" method="POST" id="form-edit-kegiatan">
    @csrf
    @method('PUT') <!-- Untuk mengubah data dengan method PUT -->
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
                    <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control" maxlength="10" value="{{ old('kode_kegiatan', $kegiatan->kode_kegiatan) }}" required>
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
                    <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('Y-m-d\TH:i')) }}" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kategori Kegiatan</label>
                    <select name="kategori_kegiatan" id="kategori_kegiatan" class="form-control" required>
                        <option value="">Pilih Kategori Kegiatan</option>
                        @foreach($kategoriKegiatan as $kategori)
                            <option value="{{ $kategori->id_kategori_kegiatan }}" {{ $kegiatan->kategori_kegiatan_id == $kategori->id_kategori_kegiatan ? 'selected' : '' }}>{{ $kategori->nama_kategori_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_kegiatan" class="error-text form-text text-danger"></small>
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
                kategori_kegiatan: {
                    required: true
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
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>