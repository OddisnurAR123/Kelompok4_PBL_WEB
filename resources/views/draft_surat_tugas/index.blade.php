@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Draft Surat Tugas</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/draft_surat_tugas/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Draft Surat Tugas</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_draft_surat_tugas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Surat</th>
                    <th>Judul Surat</th>
                    <th>Kegiatan</th>
                    <th>Tanggal Dibuat</th>
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
    #table_draft_surat_tugas th, #draft_surat_tugas td {
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

    var dataDraftSuratTugas;

    $(document).ready(function() {
        dataDraftSuratTugas = $('#table_draft_surat_tugas').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('draft_surat_tugas/list') }}",
                "dataType": "json",
                "type": "POST",
            },
            columns: [
                {
                    data: "id_draft",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "kode_surat",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "judul_surat", 
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kegiatan.nama_kegiatan", // Assuming 'kegiatan' relation exists
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "created_at",
                    className: "",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "aksi", // Action buttons
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });

    function exportPdf(id) {
        window.location.href = "{{ url('draft_surat_tugas/export_pdf') }}" + '/' + id;
    }
</script>
@endpush
