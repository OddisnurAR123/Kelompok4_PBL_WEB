@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Profil</h3>
        @if(isset($breadcrumb))
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @foreach($breadcrumb->list as $item)
                        @if(isset($item->url))
                            <li class="breadcrumb-item"><a href="{{ $item->url }}">{{ $item->label }}</a></li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">{{ $item }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>

    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
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

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <!-- Nama Pengguna -->
            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna</label>
                <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="{{ $user->nama_pengguna }}" required>
            </div>

            <!-- NIP -->
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" value="{{ $user->nip }}" required>
            </div>

            <!-- Foto Profil -->
            <div class="form-group">
                <label for="foto_profil">Foto Profil</label>
                <input type="file" name="foto_profil" id="foto_profil" class="form-control">
                @if ($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" width="100" class="mt-2 rounded">
                @endif
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
