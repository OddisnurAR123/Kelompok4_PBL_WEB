@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Jenis Pengguna</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @if(!$jenisPengguna)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Jenis Pengguna</th>
                        <td>{{ $jenisPengguna->id_jenis_pengguna }}</td>
                    </tr>
                    <tr>
                        <th>Kode Jenis Pengguna</th>
                        <td>{{ $jenisPengguna->kode_jenis_pengguna }}</td>
                    </tr>
                    <tr>
                        <th>Nama Jenis Pengguna</th>
                        <td>{{ $jenisPengguna->nama_jenis_pengguna }}</td>
                    </tr>
                </table>
            @endif
            <a href="{{ url('jenis_pengguna') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
    <!-- Tambahkan jika ada CSS khusus untuk halaman ini -->
@endpush

@push('js')
@endpush
