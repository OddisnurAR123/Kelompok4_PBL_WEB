@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan - {{ $dosen->nama_pengguna }}</h3>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Jenis Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatan as $key => $keg)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $keg->nama_kegiatan }}</td>
                        <td>{{ $keg->jenis }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada kegiatan yang diikuti</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
