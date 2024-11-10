@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori Kegiatan</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/kategori-kegiatan/import') }}')" class="btn btn-info"><i class="fa fa-file-import"></i> Import Kategori</button>
          <a href="{{ url('/kategori-kegiatan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Kategori XLSX</a>
          <a href="{{ url('/kategori-kegiatan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Kategori PDF</a>
          <button onclick="modalAction('{{ url('/kategori-kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
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
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataKategoriKegiatan;

    $(document).ready(function() {
        dataKategoriKegiatan = $('#table_kategori_kegiatan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('kategori-kegiatan/list') }}",
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
