<form action="{{ url('/detail_kegiatan/update/'.$detailKegiatan->id_detail_kegiatan) }}" method="POST" id="form-edit-detail_kegiatan">
    @csrf
    @method('PUT') <!-- Untuk mengubah data dengan method PUT -->
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sunting Progres Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kegiatan</label>
                    <input type="text" class="form-control" value="{{ $detailKegiatan->kegiatan->nama_kegiatan }}" readonly>
                    <input type="hidden" name="id_kegiatan" id="id_kegiatan" value="{{ $detailKegiatan->id_kegiatan }}">
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" maxlength="100" value="{{ old('keterangan', $detailKegiatan->keterangan) }}" required>
                    <small id="error-keterangan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Progres Kegiatan</label>
                    <input type="number" name="progres_kegiatan" id="progres_kegiatan" class="form-control" step="0.01" min="0" max="100" value="{{ old('progres_kegiatan', 0) }}" readonly required>
                    <small id="error-progres_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Beban Kerja</label>
                    <select name="beban_kerja" id="beban_kerja" class="form-control" required>
                        <option value="">Pilih Beban Kerja</option>
                        <option value="Ringan" {{ $detailKegiatan->beban_kerja == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="Sedang" {{ $detailKegiatan->beban_kerja == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Berat" {{ $detailKegiatan->beban_kerja == 'Berat' ? 'selected' : '' }}>Berat</option>
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
        const idKegiatan = $('#id_kegiatan').val();
        $.ajax({
            url: '{{ url("/get-average-progress") }}',
            type: 'GET',
            data: { id_kegiatan: idKegiatan },
            success: function(response) {
                if (response.success) {
                    $('#progres_kegiatan').val(response.averageProgress.toFixed()); // Isi dengan rata-rata
                }
            },
            error: function(xhr) {
                console.error('Gagal mengambil rata-rata progres:', xhr.responseText);
            }
        });

        $("#form-edit-detail_kegiatan").validate({
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
                    min: 0,
                    max: 100
                },
                beban_kerja: {
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
                            }).then(() => {
                                location.reload(); // Reload halaman setelah OK
                            });
                        } else {
                            $('.error-text').text(''); // Kosongkan pesan error lama
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat memperbarui detail kegiatan. Silakan coba lagi.'
                        });
                    }
                });
                return false; // Mencegah pengiriman form secara default
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