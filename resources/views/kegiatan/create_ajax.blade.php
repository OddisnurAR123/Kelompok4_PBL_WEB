@extends('layouts.master')

@section('content')
<!-- resources/views/kegiatan/create_ajax.blade.php -->
<form action="{{ url('/kegiatan/ajax') }}" method="POST" id="form-tambah-kegiatan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
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
                    <label>PIC</label>
                    <select name="pic" id="pic" class="form-control" required>
                        <option value="">Pilih PIC</option>
                        <!-- Data PIC akan dimuat lewat AJAX -->
                    </select>
                    <small id="error-pic" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Anggota</label>
                    <select name="anggota[]" id="anggota" class="form-control" multiple required>
                        <!-- Data Anggota akan dimuat lewat AJAX -->
                    </select>
                    <small id="error-anggota" class="error-text form-text text-danger"></small>
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
        $.ajax({
        url: '/pic',
        type: 'GET',
        success: function(response) {
            if (response.status) {
                // Mengisi dropdown PIC
                response.data.forEach(function(pic) {
                    $('#pic').append('<option value="' + pic.id + '">' + pic.nama_pengguna + '</option>');
                });
            }
        }
    });
    $.ajax({
        url: '/anggota',
        type: 'GET',
        success: function(response) {
            if (response.status) {
                // Mengisi dropdown Anggota
                response.data.forEach(function(anggota) {
                    $('#anggota').append('<option value="' + anggota.id + '">' + anggota.nama_pengguna + '</option>');
                });
            }
        }
    });
        $("#form-tambah-kegiatan").validate({
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
                pic: {
                    required: true
                },
                anggota: {
                    required: true,
                    minlength: 1
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide'); // Menutup modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableDetail.ajax.reload(); // Memuat ulang tabel data kegiatan
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
                $(element).addClass('is-invalid'); // Menambahkan kelas invalid-feedback pada elemen yang error
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid'); // Menghapus kelas invalid-feedback jika valid
            }
        });
    });
</script>
@endsection