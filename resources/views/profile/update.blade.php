@extends('layouts.template')

@section('title', 'Edit Profil')

@section('content')

@php
    $activeMenu = 'profile'; // Menandai menu aktif
@endphp

<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Profil Pengguna</h2>

    <!-- Menampilkan pesan sukses -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Form edit profil -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Input Nama -->
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Foto -->
                        <div class="form-group mb-3">
                            <label for="photo">Foto Profil</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Menampilkan foto profil yang sudah ada -->
                            @if ($user->photo)
                                <div class="mt-3">
                                    <img src="{{ asset($user->photo) }}" alt="Foto Profil" class="img-thumbnail" width="150">
                                    <p>Foto Profil Saat Ini</p>
                                </div>
                            @else
                                <div class="mt-3">
                                    <p>Tidak ada foto profil saat ini.</p>
                                    </div>
                            @endif
                        </div>

                        <!-- Tombol Submit -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                        </div>
                    </form>

                    <!-- Tombol Kembali -->
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary w-100 mt-2">Kembali ke Profil</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
