@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori Kegiatan</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/kategori_kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
      </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kategori Kegiatan</th>
                    <th>Nama Kategori Kegiatan</th>
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
    #table_kategori_kegiatan th, #table_kategori_kegiatan td {
        text-align: center; 
        vertical-align: middle; 
    }
</style>
@endpush


@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var datakategoriKegiatan;

    $(document).ready(function() {
        datakategoriKegiatan = $('#table_kategori_kegiatan').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('kategori_kegiatan/list') }}",
                "dataType": "json",
                "type": "POST",

            },
            columns: [
                {
                    data: "id_kategori_kegiatan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kode_kategori_kegiatan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "nama_kategori_kegiatan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });
</script>
@endpush
