<form action="{{ url('/detail_kegiatan/store') }}" method="POST" id="form-tambah-detail_kegiatan">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Progres Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">Pilih Kegiatan</option>
                        @foreach($kegiatan as $item)
                            <option value="{{ $item->id_kegiatan }}">{{ $item->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" maxlength="100" required>
                    <small id="error-keterangan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Progres Kegiatan</label>
                    <input type="number" name="progres_kegiatan" id="progres_kegiatan" class="form-control" step="0.01" min="0" max="100" value="0" readonly required>
                    <small id="error-progres_kegiatan" class="error-text form-text text-danger"></small>
                </div>            
                <div class="form-group">
                    <label>Beban Kerja</label>
                    <select name="beban_kerja" id="beban_kerja" class="form-control" required>
                        <option value="">Pilih Beban Kerja</option>
                        <option value="Ringan">Ringan</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Berat">Berat</option>
                    </select>
                    <small id="error-beban_kerja" class="error-text form-text text-danger"></small>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<style>
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        50% { transform: translateX(10px); }
        75% { transform: translateX(-10px); }
        100% { transform: translateX(0); }
    }

    .shake {
        animation: shake 0.5s ease;
    }
    .modal-header {
        background-color: #01274E;
        color: white;
    }
</style>

<script>
    $(document).ready(function() {
        $('#id_kegiatan').change(function() {
        var idKegiatan = $(this).val();

        if (idKegiatan) {
            $.ajax({
                url: '{{ route("get.averageProgress") }}',
                type: 'GET',
                data: { id_kegiatan: idKegiatan },
                success: function(response) {
                    if (response.success) {
                        $('#progres_kegiatan').val(response.averageProgress);
                    } else {
                        $('#progres_kegiatan').val(0);
                    }
                },
                error: function() {
                    $('#progres_kegiatan').val(0);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Tidak dapat mengambil data rata-rata progres.'
                    });
                }
            });
        } else {
            $('#progres_kegiatan').val(0);
        }
    });
        $("#form-tambah-detail_kegiatan").validate({
            rules: {
                id_kegiatan: {
                    required: true
                },
                keterangan: {
                    required: true,
                    minlength: 3
                },
                progres_kegiatan: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                },
                beban_kerja: {
                    required: true,
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
                            }).then(() => {
                                location.reload(); // Reload halaman untuk melihat data terbaru
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
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
                            text: 'Tidak dapat menyimpan data detail kegiatan. Coba lagi.'
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