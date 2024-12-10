<form id="form-create-agenda" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Nama Agenda -->
                <div class="form-group">
                    <label>Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
                    <small id="error-nama_agenda" class="text-danger"></small>
                </div>

                <!-- Dropdown Kegiatan -->
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                        <option value="">Pilih Kegiatan</option>
                        @foreach($kegiatan as $item)
                            <option value="{{ $item->id_kegiatan }}">{{ $item->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kegiatan" class="text-danger"></small>
                </div>
            <!-- Dropdown Pengguna -->
            <div class="form-group">
                <label>Pilih Pengguna</label>
                <select name="id_pengguna" id="id_pengguna" class="form-control">
                    <option value="">-- Pilih Pengguna --</option>
                </select>
        </div>

                <!-- Tempat Agenda -->
                <div class="form-group">
                    <label>Tempat Agenda</label>
                    <input type="text" name="tempat_agenda" id="tempat_agenda" class="form-control" required>
                    <small id="error-tempat_agenda" class="text-danger"></small>
                </div>

                <!-- Bobot Anggota -->
                <div class="form-group">
                    <label>Bobot Anggota</label>
                    <input type="number" name="bobot_anggota" id="bobot_anggota" class="form-control" min="0" step="1" required>
                    <small id="error-bobot_anggota" class="text-danger"></small>
                </div>

                <!-- Tanggal Agenda -->
                <div class="form-group">
                    <label>Tanggal Agenda</label>
                    <input type="datetime-local" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
                    <small id="error-tanggal_agenda" class="text-danger"></small>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    <small id="error-deskripsi" class="text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<!-- Script AJAX -->
<script>
 $(document).ready(function () {
    $("#form-create-agenda").validate({
        submitHandler: function (form) {
            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('agenda.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: response.message,
                        }).then(() => {
                            location.reload(); // Reload halaman setelah berhasil
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: response.message,
                        });
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors || {};
                    for (let field in errors) {
                        $(`#error-${field}`).text(errors[field][0]);
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Gagal menyimpan data. Silakan coba lagi.",
                    });
                },
            });
            return false;
        },
    });
 });

    $("#id_kegiatan").on("change", function () {
        let idKegiatan = $(this).val();
        let penggunaDropdown = $("#id_pengguna");

        penggunaDropdown.empty().append('<option value="">-- Pilih Pengguna --</option>');

        $("#id_kegiatan").on("change", function () {
    let idKegiatan = $(this).val();
    let penggunaDropdown = $("#id_pengguna");

    penggunaDropdown.empty().append('<option value="">-- Pilih Pengguna --</option>'); // Tambahkan opsi kosong

    if (idKegiatan) {
        $.ajax({
            url: "{{ route('agenda.getPengguna') }}",
            type: "GET",
            data: { id_kegiatan: idKegiatan },
            success: function (response) {
                response.forEach(function (user) {
                    penggunaDropdown.append(
                        `<option value="${user.id_pengguna}">${user.nama_pengguna}</option>`
                    );
                });
            },
            error: function () {
                penggunaDropdown.append('<option value="">Data pengguna tidak tersedia</option>');
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal memuat data pengguna.",
                });
            },
        });
    }
});
});

</script>
