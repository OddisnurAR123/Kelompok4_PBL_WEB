@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex align-items-center w-100">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-link p-0 mr-3" style="font-size: 18px;">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h3 class="card-title mb-0 flex-grow-1">Daftar Progres Kegiatan</h3>
                <div class="card-tools ml-auto">
                    <a href="{{ url('/detail_kegiatan/export_excel') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-file-excel"></i> Export XLSX
                    </a>
                    <a href="{{ url('/detail_kegiatan/export_pdf') }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                    <button onclick="modalAction('{{ url('/detail_kegiatan/create') }}')" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Tambah Progres
                    </button>
                </div>
            </div>
        </div>        
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
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

        $(document).ready(function() {
            $('#table_detail_kegiatan').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('detail_kegiatan/list') }}",
                    type: "POST",
                    dataType: "json",
                },
                columns: [
                    { data: "id_detail_kegiatan" },
                    { data: "kegiatan" },
                    { data: "keterangan" },
                    { data: "progres_kegiatan" },
                    { data: "beban_kerja" },
                    { data: "aksi", orderable: false, searchable: false } // Kolom aksi berasal dari controller
                ]
            });
        });
    </script>
@endpush