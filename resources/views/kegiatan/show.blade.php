@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detail Kegiatan</h3>
        </div>
        <div class="card-body">
            @if(empty($kegiatan))
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Kegiatan</th>
                        <td>{{ $kegiatan->id_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Kode Kegiatan</th>
                        <td>{{ $kegiatan->kode_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ $kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ $kegiatan->tanggal_selesai ? \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Kegiatan</th>
                        <td>{{ $kegiatan->kategoriKegiatan->nama_kategori_kegiatan ?? 'Tidak ada kategori' }}</td>
                    </tr>
                </table>

                <h4 class="mt-4">Anggota Kegiatan</h4>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan->anggota as $anggota)
                            <tr>
                                <td>{{ $anggota['nama_pengguna'] }}</td>
                                <td>{{ $anggota['nama_jabatan_kegiatan'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Tidak ada anggota kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
            <a href="{{ url('kegiatan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush