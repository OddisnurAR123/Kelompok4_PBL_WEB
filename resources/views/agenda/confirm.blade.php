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
                        <th class="text-right col-3">Nama Agenda :</th>
                        <td class="col-9">{{ $agenda->nama_agenda }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pengguna :</th>
                        <td class="col-9">{{ $agenda->pengguna->nama_pengguna ?? 'Tidak Ditemukan' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bobot Anggota :</th>
                        <td class="col-9">{{ $agenda->bobot_anggota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tempat Agenda :</th>
                        <td class="col-9">{{ $agenda->tempat_agenda }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Agenda :</th>
                        <td class="col-9">{{ $agenda->tanggal_agenda }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi :</th>
                        <td class="col-9">{{ $agenda->deskripsi }}</td>
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
     $(document).ready(function () {
    $("#form-delete").on('submit', function (e) {
        e.preventDefault(); // Mencegah reload halaman
        const form = this;

        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function (response) {
                if (response.status) {
                    // Sembunyikan modal jika sukses
                    $('#modal-master').modal('hide');

                    // Tampilkan notifikasi sukses dan reload halaman setelah OK
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        location.reload(); // Reload halaman setelah klik OK
                    });
                } else {
                    // Tampilkan notifikasi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal memproses permintaan, coba lagi nanti.'
                });
            }
        });
    });
});

</script>
