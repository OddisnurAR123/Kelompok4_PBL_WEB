@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Jenis Kegiatan</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @if(!$jenisKegiatan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Jenis Kegiatan</th>
                        <td>{{ $jenisKegiatan->id_kategori_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Kode Jenis Kegiatan</th>
                        <td>{{ $jenisKegiatan->kode_kategori_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Jenis Kegiatan</th>
                        <td>{{ $jenisKegiatan->nama_kategori_kegiatan }}</td>
                    </tr>
                </table>
            @endif
            <a href="{{ url('jenis_kegiatan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
