<form action="{{ url('/jabatan_kegiatan') }}" method="POST" id="form-tambah-jabatanKegiatan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Jabatan Kegiatan</label>
                    <input value="" type="text" name="kode_jabatan_kegiatan" id="kode_jabatan_kegiatan" class="form-control" required>
                    <small id="error-kode_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Jabatan Kegiatan</label>
                    <input value="" type="text" name="nama_jabatan_kegiatan" id="nama_jabatan_kegiatan" class="form-control" required>
                    <small id="error-nama_jabatan_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Is PIC</label>
                    <select name="is_pic" id="is_pic" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="1">1 -Ya-</option>
                        <option value="0">0 -Tidak-</option>
                    </select>
                    <small id="error-is_pic" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Urutan</label>
                    <input value="" type="number" name="urutan" id="urutan" class="form-control" required>
                    <small id="error-urutan" class="error-text form-text text-danger"></small>
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
    $("#form-tambah-jabatanKegiatan").validate({
        rules: {
            kode_jabatan_kegiatan: { required: true, minlength: 3 },
            nama_jabatan_kegiatan: { required: true, minlength: 3 },
            is_pic: { required: true },
            urutan: { required: true, digits: true, min: 1 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide'); // Tutup modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                                location.reload(); // Reload halaman untuk melihat data terbaru
                            });
                    } else {
                        // Menangani pesan error terkait urutan
                        if(response.message === 'Urutan sudah digunakan, silakan pilih urutan lain.') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Urutan sudah digunakan, silakan pilih urutan lain.',
                                text: response.message // Menampilkan pesan jika urutan sudah ada
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan Data',
                                text: response.message // Pesan umum jika error lainnya
                            });
                        }
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Ada masalah pada server, silakan coba lagi nanti.'
                    });
                }
            });
            return false;
        }
    });
});

</script>
