<form action="{{ url('/agenda/' . $agenda->id_agenda . '/delete') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi!!!</h5>
                    Apakah Anda yakin ingin menghapus data pengguna ini?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Kode Agenda :</th>
                        <td class="col-9">{{ $agenda->kode_agenda }}</td>
                    </tr>
                        <tr>
                            <th class="text-right col-3">Nama Agenda :</th>
                            <td class="col-9">{{ $agenda->nama_agenda }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Kegiatan :</th>
                            <td class="col-9">{{ $agenda->kegiatan->nama_kegiatan ?? 'Tidak Ada' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tempat Agenda :</th>
                            <td class="col-9">{{ $agenda->tempat_agenda }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jenis Pengguna :</th>
                            <td class="col-9">{{ $agenda->jenisPengguna->nama_jenis_pengguna ?? 'Tidak Ditemukan' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jabatan Kegiatan :</th>
                            <td class="col-9">{{ $agenda->jabatanKegiatan->nama_jabatan_kegiatan ?? 'Tidak Ditemukan' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Bobot Anggota :</th>
                            <td class="col-9">{{ $agenda->bobot_anggota }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi :</th>
                            <td class="col-9">{{ $agenda->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Agenda :</th>
                            <td class="col-9">{{ $agenda->tanggal_agenda }}</td>
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
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            // Menutup modal dan me-refresh data setelah berhasil menghapus
                            dataAgenda.ajax.reload();
                        } else {
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
            }
        });
    });
</script>