@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori Kegiatan</h3>
        <div class="card-tools">
          <button onclick="modalAction('{{ url('/kategori_kegiatan/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
      </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kategori Kegiatan</th>
                    <th>Nama Kategori Kegiatan</th>
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
    #table_kategori_kegiatan th, #table_kategori_kegiatan td {
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

    var datakategoriKegiatan;

    $(document).ready(function() {
    datakategoriKegiatan = $('#table_kategori_kegiatan').DataTable({
        serverSide: true,
        ajax: {
            "url": "{{ url('kategori_kegiatan/list') }}",
            "dataType": "json",
            "type": "POST",
        },
        columns: [
            {
                data: "id_kategori_kegiatan",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "kode_kategori_kegiatan",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "nama_kategori_kegiatan",
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
        ],
        responsive: true, // Menambahkan responsif untuk tampilan lebih baik di mobile
    });

    // Menambahkan efek loading saat memuat data
    $('#table_kategori_kegiatan').on('preXhr.dt', function(e, settings, data) {
        $('#table_kategori_kegiatan').addClass('loading');
    }).on('draw.dt', function() {
        $('#table_kategori_kegiatan').removeClass('loading');
    });
});

</script>
<style>
    /* Mengubah tampilan tabel */
    #table_kategori_kegiatan {
        width: 100%;
        border-collapse: collapse;
    }

    #table_kategori_kegiatan th, #table_kategori_kegiatan td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #dee2e6;
    }

    /* Mengubah warna header tabel */
    #table_kategori_kegiatan th {
        background-color: #11315F;
        color: white;
        font-weight: bold;
    }

    /* Efek hover pada baris tabel */
    #table_kategori_kegiatan tbody tr:hover {
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
    #table_kategori_kegiatan.loading::after {
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
