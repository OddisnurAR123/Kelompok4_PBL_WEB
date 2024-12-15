@empty($agenda)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Data yang Anda cari tidak ditemukan</h5>
            </div>
        </div>
    </div>
</div>
@else
<form action="{{ route('agenda.update', $agenda->id_agenda) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Agenda</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input value="{{ old('nama_agenda', $agenda->nama_agenda) }}" 
                           type="text" name="nama_agenda" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kegiatan</label>
                    <input type="text" value="{{ $agenda->kegiatan->nama_kegiatan }}" class="form-control" disabled>
                    <input type="hidden" name="id_kegiatan" value="{{ $agenda->id_kegiatan }}">
                </div>
                <div class="form-group">
                    <label>Pilih Nama Pengguna</label>
                    <select name="id_pengguna" class="form-control" required>
                        <option value="">-- Pilih Nama Pengguna --</option>
                        @foreach($kegiatanUser as $user)
                            <option value="{{ $user->pengguna->id_pengguna }}"
                                {{ $agenda->id_pengguna == $user->pengguna->id_pengguna ? 'selected' : '' }}>
                                {{ $user->pengguna->nama_pengguna }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tempat Agenda</label>
                    <input value="{{ old('tempat_agenda', $agenda->tempat_agenda) }}" 
                           type="text" name="tempat_agenda" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Bobot Anggota</label>
                    <input value="{{ old('bobot_anggota', $agenda->bobot_anggota) }}" 
                           type="number" name="bobot_anggota" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Agenda</label>
                    <input value="{{ old('tanggal_agenda', $agenda->tanggal_agenda ? date('Y-m-d\TH:i', strtotime($agenda->tanggal_agenda)) : '') }}" 
                           type="datetime-local" name="tanggal_agenda" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
@endempty

<script>
 $(document).ready(function () {
    $("#form-edit").on("submit", function (e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        let form = $(this);
        let actionUrl = form.attr("action");

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            success: function (response) {
                if (response.status) {
                    // Tampilkan notifikasi sukses dan reload halaman setelah klik OK
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        location.reload(); // Reload halaman setelah klik OK
                    });

                    $('#modal-master').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: response.message
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan pada server, silakan coba lagi.'
                });
            }
        });
    });
});

</script>

