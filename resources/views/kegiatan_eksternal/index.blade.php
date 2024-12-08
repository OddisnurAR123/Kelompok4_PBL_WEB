@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan Eksternal Non-JTI</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/kegiatan_eksternal/create') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan_eksternal">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kegiatan Eksternal</th>
                    <th>Waktu Kegiatan</th>
                </tr>
            </thead>
        </table>        
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<!-- Add custom styles if needed -->
@endpush

@push('js')
<script>
    // Fungsi untuk memuat konten modal
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
    $('#table_kegiatan_eksternal').DataTable({
        serverSide: true, // Menggunakan data dari server
        processing: true, // Menampilkan indikator loading
        ajax: {
            url: "{{ url('kegiatan_eksternal/list') }}", // Endpoint untuk mendapatkan data
            type: "POST", // Metode pengambilan data
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Sertakan CSRF token
            },
            error: function(xhr, error, code) {
                alert("Terjadi kesalahan: " + code); // Notifikasi error
            }
        },
        columns: [
            { data: "id_kegiatan_eksternal", title: "ID" }, // Kolom ID Jabatan Kegiatan
            { data: "nama_kegiatan_eksternal", title: "Nama Kegiatan Eksternal" }, // Kolom Nama
            { data: "waktu_kegiatan", title: "Waktu Kegiatan" }
        ],
        order: [[0, 'asc']], // Urutkan berdasarkan kolom pertama (ID)
        responsive: true, // Tambahkan responsivitas untuk tampilan mobile
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json" // Bahasa Indonesia (opsional)
        }
    });
});
</script>
<style>
    /* Mengubah tampilan tabel */
    #table_kegiatan_eksternal {
        width: 100%;
        border-collapse: collapse;
    }

    #table_kegiatan_eksternal th, #table_kegiatan_eksternal td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #dee2e6;
    }

    /* Mengubah warna header tabel */
    #table_kegiatan_eksternal th {
        background-color: #11315F;
        color: white;
        font-weight: bold;
    }

    /* Efek hover pada baris tabel */
    #table_kegiatan_eksternal tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Animasi loading (opsional, jika diperlukan) */
    #table_kegiatan_eksternal.loading::after {
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
