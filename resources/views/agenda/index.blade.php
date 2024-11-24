@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Agenda Kegiatan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/agenda/import') }}')" class="btn btn-info">Import Agenda</button>
            <a href="{{ url('/agenda/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/agenda/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
            <button onclick="modalAction('{{ url('agenda/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Agenda</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_agenda">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Agenda</th>
                    <th>Jenis Pengguna</th>
                    <th>Kegiatan</th>
                    <th>Jabatan Kegiatan</th>
                    <th>Bobot Anggota</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Tempat</th>
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

    var dataAgenda;

    $(document).ready(function() {
        dataAgenda = $('#table_agenda').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('agenda/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(s) {
                    s.jenis_pengguna_id = $('#jenis_pengguna_id').val();
                }
            },
            columns: [
                {
                    data: "id_agenda",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "nama_agenda", 
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "jenis_pengguna.nama_jenis_pengguna", // Assuming relationship
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kegiatan.nama_kegiatan", // Assuming 'kegiatan' relation
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "jabatan_kegiatan.id_jabatan_kegiatan", 
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "bobot_anggota",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "deskripsi", // Show description in the table
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tanggal_agenda",
                    className: "",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "tempat_agenda",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "aksi", // Action buttons
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#jenis_pengguna_id').on('change', function() {
            dataAgenda.ajax.reload(); // Reload data when filter changes
        });
    });
</script>
@endpush
