@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Kegiatan</h3>
        <div class="card-tools ml-auto d-flex">
            <button onclick="modalAction('{{ url('/kegiatan/import') }}')" class="btn btn-info btn-sm mr-2">
                <i class="fa fa-file-import"></i> Import Kegiatan
            </button>
            <a href="{{ url('/kegiatan/export_excel') }}" class="btn btn-primary btn-sm mr-2">
                <i class="fa fa-file-excel"></i> Export XLSX
            </a>
            <a href="{{ url('/kegiatan/export_pdf') }}" class="btn btn-warning btn-sm mr-2">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
            <button onclick="modalAction('{{ url('/kegiatan/create') }}')" class="btn btn-success btn-sm mr-2">
                <i class="fa fa-plus"></i> Tambah Kegiatan
            </button>
            <button onclick="window.location.href='{{ route('detail_kegiatan.index') }}'" class="btn btn-primary btn-sm ml-0">
                <i class="fas fa-tasks"></i>
            </button>                        
        </div>        
    </div>     
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <!-- Tab Nav -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="daftar-tab" data-bs-toggle="tab" href="#daftar" role="tab" aria-controls="daftar" aria-selected="true">Daftar Kegiatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="eksternal-tab" data-bs-toggle="tab" href="{{ route('kegiatan_eksternal') }}" role="tab" aria-controls="eksternal" aria-selected="false">Kegiatan Non-JTI</a>
            </li>            
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab Daftar Kegiatan -->
            <div class="tab-pane fade show active" id="daftar" role="tabpanel" aria-labelledby="daftar-tab">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode Kegiatan</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Periode</th>
                            <th>Kategori Kegiatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
                    data: "periode",
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