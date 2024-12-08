@extends('layouts.template')

@section('title', 'Edit Profil')

@section('content')

@php
    $activeMenu = 'profile'; // Set active menu to 'profile'
@endphp

<div class="container-fluid mt-5">
    <h2 class="text-center mb-4">Edit Profil Pengguna</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Field untuk nama -->
                        <div>
                            <label for="nama">Nama:</label>
                            <input type="text" name="nama" id="nama" value="{{ $user->nama }}" required>
                        </div>

                        <!-- Field untuk username -->
                        <div>
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username" value="{{ $user->username }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="avatar">Foto Profil</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        
                            <!-- Menampilkan foto profil saat ini jika ada -->
                            @if ($user->avatar)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil" class="img-thumbnail" width="150">
                                    <p>Foto Profil Saat Ini</p>
                                </div>
                            @else
                                <p class="mt-3">Tidak ada foto profil saat ini.</p>
                            @endif
                        </div>              

                        <button type="submit" class="btn btn-primary">Update Profil</button>
                        <a href="{{ route('profile.profil') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
