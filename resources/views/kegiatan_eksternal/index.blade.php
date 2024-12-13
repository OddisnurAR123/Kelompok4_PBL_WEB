@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan Eksternal Non-JTI</h3>
        @if(in_array(Auth::user()->id_jenis_pengguna, [3]))
            <div class="card-tools ml-auto d-flex">
                <button onclick="modalAction('{{ route('kegiatan_eksternal.create') }}')" class="btn btn-success btn-sm mr-2">
                    <i class="fa fa-plus"></i> Tambah Kegiatan
                </button>                    
            </div>   
        @endif
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
                    <th>Periode</th>
                    @if(in_array(Auth::user()->id_jenis_pengguna, [1, 2]))
                        <th>PIC</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <!-- Data akan dimuat melalui Ajax -->
            </tbody>
        </table>        
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        // Inisialisasi DataTable dengan search
        $('#table_kegiatan_eksternal').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('kegiatan_eksternal/list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                error: function(xhr, error, code) {
                    alert("Terjadi kesalahan: " + code);
                }
            },
            columns: [
                { data: "id_kegiatan_eksternal", title: "ID" },
                { data: "nama_kegiatan", title: "Nama Kegiatan Eksternal" },
                { data: "waktu_kegiatan", title: "Waktu Kegiatan" },
                { data: "periode", title: "Periode" },
                @if(in_array(Auth::user()->id_jenis_pengguna, [1, 2]))
                { data: "pic", title: "PIC" },
                @endif
            ],
            order: [[0, 'asc']],
            responsive: true,
            dom: 'lfrtip', // Menambahkan fitur search di bagian atas tabel
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json"
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
</style>
@endpush
