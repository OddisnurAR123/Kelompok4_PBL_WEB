@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Jabatan Kegiatan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/jabatan_kegiatan/create') }}')" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Data
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
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_jabatan_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Jabatan Kegiatan</th>
                    <th>Nama Jabatan Kegiatan</th>
                    <th>Is PIC</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
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
        // Inisialisasi DataTable
        $('#table_jabatan_kegiatan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('jabatan_kegiatan/list') }}",  // URL untuk mengambil data
                type: "POST",  // Menggunakan POST
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Pastikan CSRF token disertakan
                },
                error: function(xhr, error, code) {
                    alert("Error: " + code);  // Menangani error ajax
                }
            },
            columns: [
                { data: "id_jabatan_kegiatan" },  // Kolom ID Jabatan Kegiatan
                { data: "kode_jabatan_kegiatan" },  // Kolom Kode Jabatan Kegiatan
                { data: "nama_jabatan_kegiatan" },  // Kolom Nama Jabatan Kegiatan
                { data: "is_pic"},
                { data: "urutan"},
                {
                    data: "aksi",  // Kolom untuk tombol aksi (edit, delete, dll)
                    orderable: false,  // Tidak bisa diurutkan
                    searchable: false  // Tidak bisa dicari
                }
            ],
            // Opsi-opsi lain seperti pengaturan tampilan
            order: [[0, 'asc']], // Menampilkan urutan berdasarkan ID
        });
    });
</script>
@endpush
