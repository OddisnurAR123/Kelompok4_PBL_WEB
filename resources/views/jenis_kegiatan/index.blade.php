@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Kegiatan</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/jenis_kegiatan/import') }}')" class="btn btn-info"><i class="fa fa-file-import"></i> Import Jenis Kegiatan</button>
          <a href="{{ url('/jenis_kegiatan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Jenis Kegiatan XLSX</a>
          <a href="{{ url('/jenis_kegiatan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Jenis Kegiatan PDF</a>
          <button onclick="modalAction('{{ url('/jenis_kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
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
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
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
