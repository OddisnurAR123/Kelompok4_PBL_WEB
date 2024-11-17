@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Kegiatan</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_jenis_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Jenis Kegiatan</th>
                    <th>Nama Jenis Kegiatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    #table_jenis_kegiatan th, #table_jenis_kegiatan td {
        text-align: center; 
        vertical-align: middle; 
    }
</style>
@endpush


@push('js')
<script>
    function modalAction(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#modal-master').html(response); 
                $('#modal-master').modal('show'); 
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                Swal.fire('Kesalahan', 'Data tidak ditemukan atau terjadi kesalahan server.', 'error');
            }
        });
    }

    var dataJenisKegiatan;
    $(document).ready(function() {
    dataJenisKegiatan = $('#table_jenis_kegiatan').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('jenis_kegiatan/list') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        },
        columns: [
            { data: "id_kategori_kegiatan" },
            { data: "kode_kategori_kegiatan" },
            { data: "nama_kategori_kegiatan" },
            { data: "aksi", orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
