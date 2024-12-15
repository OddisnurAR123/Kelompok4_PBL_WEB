@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Kegiatan</h3>
        <div class="card-tools ml-auto d-flex">
            @if(Auth::user()->id_jenis_pengguna == 1)
                <a href="{{ url('/kegiatan/export_pdf') }}" class="btn btn-warning btn-sm mr-2">
                    <i class="fa fa-file-pdf"></i> Export
                </a>
                <button onclick="modalAction('{{ url('/kegiatan/create') }}')" class="btn btn-success btn-sm mr-2">
                    <i class="fa fa-plus"></i> Tambah Kegiatan
                </button>
            @endif      
            <!-- Tombol Detail Kegiatan hanya muncul jika pengguna memiliki id_jabatan_kegiatan 1 -->
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

        <!-- Filter berdasarkan periode -->
        <div class="d-flex justify-content-between">
            <div class="form-group">
                <label for="periode_filter">Periode</label>
                <select class="form-control" id="periode_filter">
                    <!-- Pilihan periode akan diisi oleh JavaScript -->
                </select>
            </div>
        </div>

        <!-- Tab Nav -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="daftar-tab" data-bs-toggle="tab" href="#daftar" role="tab" aria-controls="daftar" aria-selected="true">Daftar Kegiatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="eksternal-tab" data-bs-toggle="tab" href="{{ route('kegiatan_eksternal.index') }}" role="tab" aria-controls="eksternal" aria-selected="false">Kegiatan Eksternal</a>
            </li>            
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab Daftar Kegiatan -->
            <div class="tab-pane fade show active" id="daftar" role="tabpanel" aria-labelledby="daftar-tab">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                    <thead>
                        <tr>
                            @if(Auth::user()->id_jenis_pengguna == 2)
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            @else
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
                            @endif
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

    var dataKegiatan;

    $(document).ready(function() {
        // Menghitung 5 tahun terakhir
        var currentYear = new Date().getFullYear();
        var years = [];
        for (var i = currentYear; i > currentYear - 5; i--) {
            years.push(i);
        }

        // Menambahkan opsi tahun ke dalam dropdown
        var periodeFilter = $('#periode_filter');
        periodeFilter.empty(); // Menghapus opsi yang ada sebelumnya
        periodeFilter.append('<option value="">Semua Periode</option>'); // Opsi untuk semua periode
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
                // Menambahkan parameter periode_filter ke request
                d.periode_filter = $('#periode_filter').val();
            }
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
                    data: "tempat_kegiatan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "status",
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
        // Event listener untuk filter periode
        $('#periode_filter').change(function() {
            dataKegiatan.draw(); // Reload table dengan filter yang baru
        });
    });
</script>
@endpush