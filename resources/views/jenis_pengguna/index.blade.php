@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Pengguna</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/jenis_pengguna/import') }}')" class="btn btn-info"><i class="fa fa-file-import"></i> Import Jenis Pengguna</button>
          <a href="{{ url('/jenis_pengguna/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Jenis Pengguna XLSX</a>
          <a href="{{ url('/jenis_pengguna/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Jenis Pengguna PDF</a>
          <button onclick="modalAction('{{ url('/jenis_pengguna/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_jenis_pengguna">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Jenis Pengguna</th>
                    <th>Nama Jenis Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataJenisPengguna;

    $(document).ready(function() {
        dataJenisPengguna = $('#table_jenis_pengguna').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('jenis_pengguna/list') }}",
                "dataType": "json",
                "type": "POST",
            },
            columns: [
                {
                    data: "id_jenis_pengguna",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kode_jenis_pengguna",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "nama_jenis_pengguna",
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
