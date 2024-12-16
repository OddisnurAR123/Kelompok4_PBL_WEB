@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Kegiatan</h3>
        <div class="card-tools ml-auto d-flex">
            @if(Auth::user()->id_jenis_pengguna == 1)
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
            @endif      
            @if(Auth::user()->jabatanKegiatans()->where('is_pic', 1)->exists())
                <button onclick="window.location.href='{{ route('detail_kegiatan.index') }}'" class="btn btn-primary btn-sm ml-0">
                    <i class="fas fa-tasks"></i>
                </button>
            @endif
        </div>        
    </div>      
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between">
            <div class="form-group">
                <label for="periode_filter">Periode</label>
                <select class="form-control" id="periode_filter">
                </select>
            </div>
        </div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="daftar-tab" data-bs-toggle="tab" href="#daftar" role="tab" aria-controls="daftar" aria-selected="true">Daftar Kegiatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="eksternal-tab" data-bs-toggle="tab" href="{{ route('kegiatan_eksternal.index') }}" role="tab" aria-controls="eksternal" aria-selected="false">Kegiatan Eksternal</a>
            </li>            
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="daftar" role="tabpanel" aria-labelledby="daftar-tab">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                    <thead>
                        <tr>
                            @if(Auth::user()->id_jenis_pengguna == [1, 3])
                                <th>ID</th>
                                <th>Kode Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Periode</th>
                                <th>Kategori Kegiatan</th>
                                <th>Tempat Kegiatan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            @else
                                <th>ID</th>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>        
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
<style>
    .table th {
        background-color: #01274E;
        color: #f8f9fa;
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

    $(document).ready(function() {
        var currentYear = new Date().getFullYear();
        var years = [];
        for (var i = currentYear; i > currentYear - 5; i--) {
            years.push(i);
        }

        var periodeFilter = $('#periode_filter');
        periodeFilter.empty();
        periodeFilter.append('<option value="">Semua Periode</option>');
        years.forEach(function(year) {
            periodeFilter.append('<option value="' + year + '">' + year + '</option>');
        });

        var dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('kegiatan/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.periode_filter = $('#periode_filter').val();
                }
            },
            columns: [
                @if(Auth::user()->id_jenis_pengguna == 3)
                    { data: "id_kegiatan", orderable: true, searchable: true },
                    { data: "kode_kegiatan", orderable: true, searchable: true },
                    { data: "nama_kegiatan", orderable: true, searchable: true },
                    { data: "tanggal_mulai", orderable: true, searchable: true },
                    { data: "tanggal_selesai", orderable: true, searchable: true },
                    { data: "periode", orderable: true, searchable: true },
                    { data: "kategori_kegiatan", orderable: true, searchable: true },
                    { data: "tempat_kegiatan", orderable: true, searchable: true },
                    { data: "status", orderable: true, searchable: true },
                    { data: "aksi", orderable: false, searchable: false }
                @else
                    { data: "id_kegiatan", orderable: true, searchable: true },
                    { data: "nama_kegiatan", orderable: true, searchable: true },
                    { data: "status", orderable: true, searchable: true },
                    { data: "aksi", orderable: false, searchable: false }
                @endif
            ]
        });

        $('#periode_filter').change(function() {
            dataKegiatan.draw();
        });
    });
</script>
@endpush