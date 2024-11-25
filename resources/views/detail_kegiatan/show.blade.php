@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detail Kegiatan</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($detail_kegiatan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Kegiatan</th>
                        <td>{{ $detail_kegiatan->id_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $detail_kegiatan->keterangan }}</td>
                    </tr>
                    <tr>
                        <th>Progres Kegiatan</th>
                        <td>{{ $detail_kegiatan->progress_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Beban Kerja</th>
                        <td>{{ $detail_kegiatan->beban_kerja }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('detail_kegiatan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush