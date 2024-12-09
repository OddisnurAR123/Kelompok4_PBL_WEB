<form action="{{ route('kegiatan_eksternal.store') }}" method="POST" id="form-tambah-kegiatanEksternal">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kegiatan Eksternal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan') }}" required>
                </div>
                <div class="form-group">
                    <label for="waktu_kegiatan">Waktu Kegiatan</label>
                    <input type="date" name="waktu_kegiatan" id="waktu_kegiatan" class="form-control" value="{{ old('waktu_kegiatan') }}" required>
                </div>
                <div class="form-group">
                    <label>Periode Kegiatan</label>
                    <input type="number" name="periode" id="periode" class="form-control" placeholder="Masukkan Tahun" length="4" required>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
                </div>  
                <div class="form-group">
                    <label for="pic">PIC (Penanggung Jawab)</label>
                    <input type="text" name="pic" id="pic" class="form-control" value="{{ auth()->user()->nama_pengguna ?? 'Unknown' }}" readonly>
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
        // Dapatkan nama pengguna yang sedang login melalui atribut hidden
        let namaPengguna = $('#pic').val();

        // Set nama pengguna di form
        $('#pic').val(namaPengguna);

        $("#form-tambah-kegiatanEksternal").validate({
            rules: {
                nama_kegiatan: { required: true, minlength: 3 },
                waktu_kegiatan: { required: true, date: true },
                periode: { required: true, maxlength: 4 }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            // SweetAlert untuk notifikasi sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                // Auto reload setelah tombol OK ditekan
                                location.reload();
                            });
                        } else {
                            // Menangani kesalahan jika response.status == false
                            $('.error-text').text(''); // Menghapus pesan error sebelumnya
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]); // Menampilkan pesan error untuk masing-masing field
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function () {
                        // Menangani kesalahan server
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat menyimpan data kegiatan. Coba lagi.'
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