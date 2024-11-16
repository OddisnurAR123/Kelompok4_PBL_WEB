@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Jabatan Kegiatan</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @if(!$jabatanKegiatan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Jabatan Kegiatan</th>
                        <td>{{ $jabatanKegiatan->id_jabatan_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Kode Jabatan Kegiatan</th>
                        <td>{{ $jabatanKegiatan->kode_jabatan_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Jabatan Kegiatan</th>
                        <td>{{ $jabatanKegiatan->nama_jabatan_kegiatan }}</td>
                    </tr>
                </table>
            @endif
            <a href="{{ url('jabatan_kegiatan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
    <!-- Tambahkan jika ada CSS khusus untuk halaman ini -->
@endpush

@push('js')
@endpush
