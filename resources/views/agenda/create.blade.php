<form id="form-create-agenda" enctype="multipart/form-data">
    @csrf

    <!-- Hidden input untuk ID Kegiatan -->
    <input type="hidden" name="id_kegiatan" id="id_kegiatan" value="{{ $kegiatan->id_kegiatan }}">
    <!-- Nama Agenda -->
    <div class="form-group">
        <label>Nama Agenda</label>
        <input type="text" name="nama_agenda" id="nama_agenda" class="form-control" required>
        <small id="error-nama_agenda" class="text-danger"></small>
    </div>

   <!-- Dropdown Pilih Pengguna -->
<!-- Dropdown Pilih Pengguna -->
<div class="form-group">
    <label>Pilih Pengguna</label>
    <select name="id_pengguna" id="id_pengguna" class="form-control" required></select>
</div>
<!-- Tempat Agenda -->
<div class="form-group">
    <label>Bobot Anggota</label>
    <input type="text" name="bobot_anggota" id="bobot_anggota" class="form-control" required>
</div>

    <!-- Tempat Agenda -->
    <div class="form-group">
        <label>Tempat Agenda</label>
        <input type="text" name="tempat_agenda" id="tempat_agenda" class="form-control" required>
    </div>

    <!-- Tanggal Agenda -->
    <div class="form-group">
        <label>Tanggal Agenda</label>
        <input type="datetime-local" name="tanggal_agenda" id="tanggal_agenda" class="form-control" required>
    </div>

    <!-- Deskripsi -->
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<!-- AJAX Script -->
<script>
$(document).ready(function() {
    function loadPengguna(idKegiatan) {
        $.ajax({
            url: "{{ route('agenda.getPengguna') }}",
            type: "GET",
            data: { id_kegiatan: idKegiatan },
            success: function(response) {
                let penggunaDropdown = $("#id_pengguna");
                penggunaDropdown.empty().append('<option value="">-- Pilih Pengguna --</option>');

                response.forEach(function(user) {
                    penggunaDropdown.append(`<option value="${user.id_pengguna}">${user.nama_pengguna}</option>`);
                });
            },
            error: function() {
                Swal.fire('Gagal', 'Data pengguna tidak tersedia.', 'error');
            }
        });
    }

    // Mengisi dropdown ketika form dibuka
    let idKegiatan = $("#id_kegiatan").val();
    if (idKegiatan) {
        loadPengguna(idKegiatan);
    }
});
$("#form-create-agenda").submit(function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    for (let [key, value] of formData.entries()) {
        console.log(key, value); // Debugging form data
    }

    $.ajax({
        url: "{{ route('agenda.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire('Sukses', response.message, 'success')
                .then(() => location.reload());
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function(key, error) {
                        $(`#error-${key}`).text(error[0]);
                    });
                }
            } else {
                Swal.fire('Gagal', 'Terjadi kesalahan server.', 'error');
                console.error('Server Error:', xhr.responseText);
                console.error('Status:', xhr.status);
            }
        }
    });
});

</script>