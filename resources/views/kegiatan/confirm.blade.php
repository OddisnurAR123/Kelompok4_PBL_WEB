@empty($kegiatan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kegiatan/' . $kegiatan->id_kegiatan . '/delete') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data kegiatan seperti di bawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Kode Kegiatan :</th>
                            <td class="col-9">{{ $kegiatan->kode_kegiatan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Kegiatan :</th>
                            <td class="col-9">{{ $kegiatan->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Mulai :</th>
                            <td class="col-9">{{ $kegiatan->tanggal_mulai }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Selesai :</th>
                            <td class="col-9">{{ $kegiatan->tanggal_selesai }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Periode :</th>
                            <td class="col-9">{{ $kegiatan->periode }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Kategori Kegiatan :</th>
                            <td class="col-9">
                                @isset($kegiatan->kategoriKegiatan)
                                    {{ $kegiatan->kategoriKegiatan->nama_kategori_kegiatan ?? 'Tidak Ada' }}
                                @else
                                    Tidak Ditemukan
                                @endisset
                            </td>
                        </tr> 
                        <tr>
                            <th class="text-right col-3">Tempat Kegiatan :</th>
                            <td class="col-9">{{ $kegiatan->tempat_kegiatan }}</td>
                        </tr>                      
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
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
        .table th, .table td {
            background-color: white;
            color: black;
        }
    </style>

    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                                tableKegiatan.ajax.reload();
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
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Terjadi kesalahan saat memproses permintaan, silakan coba lagi.'
                            });
                        }
                    });
                    return false;
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
@endempty
