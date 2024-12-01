@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Pengguna</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_jenis_pengguna">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Jenis Pengguna</th>
                    <th>Nama Jenis Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan dimuat secara dinamis oleh DataTables -->
            </tbody>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    #table_jenis_pengguna th, #table_jenis_pengguna td {
        text-align: center; 
        vertical-align: middle; 
        padding: 10px;
    }

    #table_jenis_pengguna th {
        background-color: #11315F;
        color: white;
        font-weight: bold;
    }

    #table_jenis_pengguna tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Styling untuk modal */
    #myModal .modal-content {
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }

    #myModal .modal-header {
        background-color: #11315F;
        color: white;
    }

    #myModal .modal-footer {
        background-color: #f8f9fa;
    }

    /* Animasi shake untuk modal */
    .animate.shake {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
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
        // Cek apakah DataTable sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#table_jenis_pengguna')) {
            $('#table_jenis_pengguna').DataTable().destroy();
        }

        // Inisialisasi DataTable
        $('#table_jenis_pengguna').DataTable({
            processing: true, // Tampilkan animasi loading
            serverSide: true, // Server-side processing
            ajax: {
                url: "{{ url('jenis_pengguna/list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: "id_jenis_pengguna", orderable: true, searchable: true },
                { data: "kode_jenis_pengguna", orderable: true, searchable: true },
                { data: "nama_jenis_pengguna", orderable: true, searchable: true },
                { data: "aksi", orderable: false, searchable: false }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json" // Bahasa Indonesia
            },
            responsive: true // Responsif untuk perangkat kecil
        });
    });
</script>
@endpush
