@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header text-center text-white py-4" style="background-color: #01274E;">
            <h2 class="mb-0">Sunting Foto Profil</h2>
        </div>
        <div class="card-body">
            <!-- Feedback Error -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Form Upload Foto -->
            <form method="POST" action="{{ route('profile.update-photo') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3 text-center">
                    <!-- Foto Profil Saat Ini -->
                    @if (auth()->user()->foto_profil)
                        <img src="{{ asset('storage/profile/' . auth()->user()->foto_profil) }}" 
                             alt="Foto Profil" 
                             class="rounded-circle img-fluid shadow-sm mb-3" 
                             style="width: 160px; height: 160px; object-fit: cover;">
                    @else
                        <div class="placeholder-profile text-white d-flex align-items-center justify-content-center rounded-circle shadow-sm mb-3" 
                             style="width: 160px; height: 160px; font-size: 50px; background-color: #01274E;">
                        </div>
                        <p class="text-muted">Foto belum diupload</p>
                    @endif
                </div>

                <!-- Input File Foto Baru -->
                <div class="mb-3">
                    <label for="foto_profil" class="form-label">Unggah Foto Baru</label>
                    <input type="file" name="foto_profil" id="foto_profil" 
                           class="form-control" 
                           accept="image/*" 
                           required>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-custom-primary btn-md px-4 py-2 rounded-pill shadow-sm">Simpan Perubahan</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-md px-4 py-2 rounded-pill shadow-sm">Batal</a>
                </div>
            </form>
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
        width: 160px;
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #01274E;
        color: #fff;
        font-size: 50px;
        border-radius: 50%;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
