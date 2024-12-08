@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/pengguna/import') }}')" class="btn btn-info">Import Pengguna</button>
            <a href="{{ url('/pengguna/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/pengguna/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
            <button onclick="modalAction('{{ url('/pengguna/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Pengguna</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_pengguna">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Pengguna</th>
                    <th>Nama Pengguna</th>
                    <th>Username</th>
                    <th>NIP</th>
                    <th>Email</th>
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

    var dataPengguna;

    $(document).ready(function() {
        dataPengguna = $('#table_pengguna').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('pengguna/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.id_jenis_pengguna = $('#id_jenis_pengguna').val();
                }
            },
            columns: [
                {
                    data: "id_pengguna", // ID Pengguna
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "jenis_pengguna.nama_jenis_pengguna", // Jenis Pengguna dari relasi
                    className: "text-left",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "nama_pengguna", // Nama Pengguna
                    className: "text-left",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "username", // Username
                    className: "text-left",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "nip", // NIP
                    className: "text-left",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "email", // Email
                    className: "text-left",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi", // Aksi
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Filter berdasarkan jenis pengguna
        $('#id_jenis_pengguna').on('change', function() {
            dataPengguna.ajax.reload();
        });
    });

</script>
<style>
    /* Mengubah tampilan tabel */
    #table_pengguna {
        width: 100%;
        border-collapse: collapse;
    }

    #table_pengguna th, #table_pengguna td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #dee2e6;
    }

    /* Mengubah warna header tabel */
    #table_pengguna th {
        background-color: #11315F;
        color: white;
        font-weight: bold;
    }

    /* Efek hover pada baris tabel */
    #table_pengguna tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Menambahkan border dan rounded corners untuk tombol */
    .btn-success {
        border-radius: 25px;
    }

    /* Menambahkan efek hover pada tombol */
    .btn-success:hover {
        background-color: #28a745;
        border-color: #28a745;
        transition: background-color 0.3s ease;
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

    /* Menambahkan animasi loading */
    #table_pengguna.loading::after {
        content: "Memuat Data...";
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font-size: 18px;
        font-weight: bold;
        color: #007bff;
        opacity: 0.7;
    }
</style>

@endpush
