<!-- resources/views/kegiatan/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Kegiatan</h3>
    
    <!-- Menampilkan form edit kegiatan -->
    <form id="editKegiatanForm" action="{{ route('kegiatan.update_ajax', $kegiatan->id_kegiatan) }}" method="POST">
        @csrf
        @method('PUT') 
        <div class="form-group">
            <label for="kode_kegiatan">Kode Kegiatan</label>
            <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan" value="{{ $kegiatan->kode_kegiatan }}" required>
        </div>
        <div class="form-group">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required>
        </div>
        <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $kegiatan->tanggal_mulai->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $kegiatan->tanggal_selesai->format('Y-m-d') }}" required>
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
        <button type="submit" class="btn btn-primary">Update Kegiatan</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // AJAX untuk mendapatkan data PIC
        $.ajax({
            url: '/pic',
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    // Mengisi dropdown PIC
                    response.data.forEach(function(pic) {
                        $('#pic').append('<option value="' + pic.id + '">' + pic.nama_pengguna + '</option>');
                    });
                    // Set nilai default PIC (jika ada)
                    $('#pic').val("{{ $kegiatan->pic_id }}");
                }
            }
        });

        // AJAX untuk mendapatkan data Anggota
        $.ajax({
            url: '/anggota',
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    // Mengisi dropdown Anggota
                    response.data.forEach(function(anggota) {
                        $('#anggota').append('<option value="' + anggota.id + '">' + anggota.nama_pengguna + '</option>');
                    });
                    // Set nilai default Anggota (jika ada)
                    $('#anggota').val({{ json_encode($kegiatan->anggota->pluck('id')->toArray()) }});
                }
            }
        });

        // Validasi form menggunakan jQuery Validate
        $("#editKegiatanForm").validate({
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
            },
            submitHandler: function(form) {
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(function() {
                                window.location.href = "{{ route('kegiatan.index') }}"; // Redirect ke halaman kegiatan setelah sukses
                            });
                        } else {
                            $('.error-text').text(''); // Clear error messages
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]); // Show specific error message for each field
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
                return false; // Prevent form from submitting normally
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
@endpush