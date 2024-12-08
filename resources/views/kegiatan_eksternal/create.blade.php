@extends('layouts.template')

@section('content')
<div class="container">
    <a href="{{ route('kegiatan.index') }}" class="btn btn-link p-0 mr-3" style="font-size: 18px;">
        <i class="fa fa-arrow-left"></i>
    </a>
    <h1>Tambah Kegiatan Eksternal</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kegiatan_eksternal.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan') }}" required>
        </div>
        <div class="form-group">
            <label for="waktu_kegiatan">Waktu Kegiatan</label>
            <input type="date" name="waktu_kegiatan" id="waktu_kegiatan" class="form-control" value="{{ old('waktu_kegiatan') }}" required>
        </div>
        <div class="form-group">
            <label for="pic">PIC (Penanggung Jawab)</label>
            <input type="text" name="pic" id="pic" class="form-control" value="{{ old('pic') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
