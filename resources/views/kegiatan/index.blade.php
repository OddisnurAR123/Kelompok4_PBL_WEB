@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/kegiatan/import') }}')" class="btn btn-info"><i class="fa fa-file-import"></i> Import Kegiatan</button>
          <a href="{{ url('/kegiatan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Kegiatan XLSX</a>
          <a href="{{ url('/kegiatan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Kegiatan PDF</a>
          <button onclick="modalAction('{{ url('/kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Kegiatan</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="kegiatan-tab" data-bs-toggle="tab" href="#kegiatan" role="tab" aria-controls="kegiatan" aria-selected="true">Kegiatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="anggota-tab" data-bs-toggle="tab" href="#anggota" role="tab" aria-controls="anggota" aria-selected="false">Anggota Kegiatan</a>
            </li>
        </ul>
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kegiatan</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Kategori Kegiatan</th>
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

    var dataKegiatan;

    $(document).ready(function() {
        dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('kegiatan/list') }}",
                "dataType": "json",
                "type": "POST",
            },
            columns: [
                {
                    data: "id_kegiatan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                data: "kode_kegiatan",
                className: "",
                orderable: true,
                searchable: true
                },
                {
                    data: "nama_kegiatan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tanggal_mulai",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tanggal_selesai",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kategori_kegiatan",
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