@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Progres kegiatan</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/detail_kegiatan/import') }}')" class="btn btn-info"><i class="fa fa-file-import"></i> Import progres kegiatan</button>
          <a href="{{ url('/detail_kegiatan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export progres kegiatan XLSX</a>
          <a href="{{ url('/detail_kegiatan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export progres kegiatan PDF</a>
          <button onclick="modalAction('{{ url('/detail_kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah progres kegiatan</button>
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
                    <th>Kegiatan</th>
                    <th>Keterangan</th>
                    <th>Progres Kegiatan</th>
                    <th>Beban Kerja</th>
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
    var datadetail_kegiatan;

$(document).ready(function() {
    datadetail_kegiatan = $('#table detail_kegiatan').DataTable({
        serverSide: true,
        ajax: {
            "url": "{{ url('detail_kegiatan/list') }}",
            "dataType": "json",
            "type": "POST",
        },
        columns: [
            {
                data: "id_detail_kegiatan",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "id_kegiatan",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "keterangan",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "progress_kegiatan",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "beban_kerja",
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