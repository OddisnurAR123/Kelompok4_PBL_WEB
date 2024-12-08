@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Profil</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna</label>
                <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="{{ $user->nama_pengguna }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control">
                <small>* Kosongkan jika tidak ingin mengubah password</small>
            </div>
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" value="{{ $user->nip }}" required>
            </div>
            <div class="form-group">
                <label for="foto_profil">Foto Profil</label>
                <input type="file" name="foto_profil" id="foto_profil" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
