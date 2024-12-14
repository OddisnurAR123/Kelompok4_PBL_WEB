<form action="{{ route('kegiatan.upload', ['id' => $id_kegiatan]) }}" method="POST" id="form-upload-surat-tugas" enctype="multipart/form-data">
    @csrf
    <div id="modal-upload-surat-tugas" class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title" id="exampleModalLabel">Unggah Surat Tugas</h5> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div> 
            <div class="modal-body"> 
                <div class="form-group"> 
                    <label>Pilih File Surat Tugas</label> 
                    <input type="file" name="file_surat_tugas" id="file_surat_tugas" class="form-control" required> 
                    <small id="error-file_surat_tugas" class="error-text form-text text-danger"></small> 
                </div> 
            </div> 
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button> 
                <button type="submit" class="btn btn-primary">Upload</button> 
            </div> 
        </div> 
    </div> 
</form> 

<script> 
    $(document).ready(function() { 
        $("#form-upload-surat-tugas").validate({ 
            rules: { 
                file_surat_tugas: {required: true, extension: "pdf,doc,docx"}, 
            }, 
            submitHandler: function(form) {  
                var formData = new FormData(form);  // Jadikan form ke FormData untuk menghandle file 
                
                $.ajax({
    url: "{{ route('kegiatan.upload', ['id' => $id_kegiatan]) }}",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        if (response.status) {
            $('#modal-upload-surat-tugas').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: response.message
            });
        }
    },
    error: function(xhr) {
        console.error('AJAX Error:', xhr.responseText);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal mengunggah file.'
        });
    }
});
                return false; 
            }, 
            errorElement: 'span', 
            errorPlacement: function (error, element) { 
                error.addClass('invalid-feedback'); 
                element.closest('.form-group').append(error); 
            }, 
            highlight: function (element, errorClass, validClass) { 
                $(element).addClass('is-invalid'); 
            }, 
            unhighlight: function (element, errorClass, validClass) { 
                $(element).removeClass('is-invalid'); 
            } 
        }); 
    }); 
</script>
