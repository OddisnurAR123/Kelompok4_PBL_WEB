@empty($jabatanKegiatan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/jabatan_kegiatan/' . $jabatanKegiatan->id_jabatan_kegiatan . '/delete') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Jabatan Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi!!!</h5>
                    Apakah Anda yakin ingin menghapus data jabatan kegiatan ini?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th style="width: 40%;">ID Jabatan Kegiatan:</th>
                        <td class="col-9">{{ $jabatanKegiatan->id_jabatan_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Kode Jabatan Kegiatan:</th>
                        <td class="col-9">{{ $jabatanKegiatan->kode_jabatan_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Nama Jabatan Kegiatan:</th>
                        <td class="col-9">{{ $jabatanKegiatan->nama_jabatan_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Is PIC:</th>
                        <td class="col-9">{{ $jabatanKegiatan->is_pic }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%;">Urutan:</th>
                        <td class="col-9">{{ $jabatanKegiatan->urutan }}</td>
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
                        }).then(() => {
                                location.reload(); // Reload halaman untuk melihat data terbaru
                        });;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        }
    });
});
</script>
@endempty
