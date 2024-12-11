@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header text-center text-white py-4" style="background-color: #01274E;">
                    <h2 class="mb-0">Edit Profil</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4 d-flex justify-content-center mb-4 mb-md-0">
                                <!-- Foto Profil -->
                            <div class="col-md-4 d-flex justify-content-center mb-4 mb-md-0">
                                <div class="text-center">
                                    <div class="mb-3 position-relative">
                                        @if ($user->foto_profil)
                                            <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                                                alt="Foto Profil" 
                                                class="rounded-circle img-fluid shadow-sm" 
                                                style="width: 160px; height: 160px; object-fit: cover;">
                                        @else
                                            <div class="placeholder-profile text-white d-flex align-items-center justify-content-center rounded-circle shadow-sm" 
                                                style="width: 160px; height: 160px; font-size: 50px; background-color: #01274E;">
                                                ?
                                            </div>
                                            <p class="mt-2 text-muted">Foto belum diupload</p>
                                        @endif
                                    </div>
                                    <!-- Input File Diletakkan di Bawah Foto Profil -->
                                    <div class="mb-3">
                                        <label for="foto_profil" class="form-label">Upload Foto Profil</label>
                                        <input type="file" name="foto_profil" id="foto_profil" class="form-control">
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-8">
                                <!-- Nama Pengguna -->
                                <div class="mb-3">
                                    <label for="nama_pengguna" class="form-label">Nama</label>
                                    <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="{{ old('nama_pengguna', $user->nama_pengguna) }}" required>
                                </div>

                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru" style="opacity: 0.7;">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" style="opacity: 0.7;">
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-custom-primary btn-md px-4 py-2 rounded-pill shadow-sm">Simpan Perubahan</button>
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-md px-4 py-2 rounded-pill shadow-sm">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .btn-custom-primary {
        background-color: #01274E;
        border: none;
        color: #fff;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .btn-secondary {
        background-color: #ddd;
        border: none;
        color: #01274E;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .placeholder-profile {
        background-color: #ddd;
        color: #fff;
    }
</style>
@endpush
