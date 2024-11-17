@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Detail Kegiatan</h3>
        <div class="card-tools">
        <button onclick="modalAction('{{ url('/detail-kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_detail_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Lokasi Kegiatan</th>
                    <th>Status</th>
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
    #table_detail_kegiatan th, #table_detail_kegiatan td {
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

    var dataDetailKegiatan;
    $(document).ready(function() {
        dataDetailKegiatan = $('#table_detail_kegiatan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('detail-kegiatan/list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: "id_kegiatan" },
                { data: "nama_kegiatan" },
                { data: "tanggal_kegiatan" },
                { data: "lokasi_kegiatan" },
                { data: "status_kegiatan" },
                { 
                    data: "aksi", 
                    orderable: false, 
                    searchable: false 
                }
            ]
        });
    });
</script>
@endpush
