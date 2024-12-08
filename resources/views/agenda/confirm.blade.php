<form action="{{ url('/agenda/' . $agenda->id_agenda . '/delete') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi!!!</h5>
                    Apakah Anda yakin ingin menghapus data agenda ini?
                </div>

                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Agenda:</th>
                        <td>{{ $agenda->nama_agenda }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kegiatan:</th>
                        <td>{{ $agenda->kegiatan->nama_kegiatan ?? 'Tidak Ada' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tempat Agenda:</th>
                        <td>{{ $agenda->tempat_agenda }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pengguna:</th>
                        <td>{{ $agenda->kegiatanUser->nama_pengguna ?? 'Tidak Ditemukan' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jabatan Pengguna:</th>
                        <td class="col-9">{{ $agenda->kegiatanUser->jabatanKegiatan->nama_jabatan_kegiatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bobot Anggota:</th>
                        <td>{{ $agenda->bobot_anggota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi:</th>
                        <td>{{ $agenda->deskripsi ?? 'Tidak Ada Deskripsi' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Agenda:</th>
                        <td>{{ $agenda->tanggal_agenda }}</td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            // Menghapus data dari tabel di halaman tanpa reload
                            dataAgenda.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat memproses permintaan, silakan coba lagi.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
