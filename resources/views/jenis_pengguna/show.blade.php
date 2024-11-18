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


{{-- @empty($jenisPengguna)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang anda cari tidak ditemukan
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-warning">Kembali</a> <!-- Tombol untuk kembali ke halaman sebelumnya -->
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Level</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">ID Level:</th>
                    <td class="col-9">{{ $jenisPengguna->id_jenis_pengguna }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Kode Level:</th>
                    <td class="col-9">{{ $jenisPengguna->kode_jenis_kegiatan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Level:</th>
                    <td class="col-9">{{ $jenisPengguna->nama_jenis_kegiatan }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
@endempty --}}
