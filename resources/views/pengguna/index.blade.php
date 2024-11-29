@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
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
                    <th>Nama Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Jenis Pengguna</th>
                    <th>Foto Profil</th>
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
            },
            columns: [
                {
                    data: "id_pengguna",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "nama_pengguna",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "username",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "email",
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
                    data: "foto_profil",
                    className: "",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<img src="${data}" alt="Foto Profil" class="img-thumbnail" style="width: 50px; height: 50px;">`;
                    }
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
