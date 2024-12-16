@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header text-center text-white py-4" style="background-color: #01274E;">
            <h2 class="mb-0">Profil Saya</h2>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Foto Profil -->
                <div class="col-md-4 d-flex justify-content-center mb-4 mb-md-0">
                    <div class="mb-3 position-relative">
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
                </div>

                <!-- Informasi Pengguna -->
                <div class="col-md-8">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-user text-primary"></i> 
                            <strong>Nama:</strong> {{ $user->nama_pengguna }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user-tag text-primary"></i> 
                            <strong>Username:</strong> {{ $user->username }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-id-card text-primary"></i> 
                            <strong>NIP:</strong> {{ $user->nip }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-envelope text-primary"></i> 
                            <strong>Email:</strong> {{ $user->email }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-lock text-primary"></i> 
                            <strong>Password:</strong> ******
                        </li>
                    </ul>

                    <!-- Button Edit Foto Profil -->
                    <div class="mt-3">
                        <a href="{{ route('profile.edit-photo') }}" class="btn btn-primary btn-block mt-2">
                            Edit Foto Profil
                        </a>
                    </div>

                    <!-- Button Edit Password -->
                    <div class="mt-3">
                        <a href="{{ route('profile.edit-password') }}" class="btn btn-secondary btn-block mt-2">
                            Edit Password
                        </a>
                    </div>
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

    .bg-gradient-primary {
        background: linear-gradient(45deg, #004085, #0056b3);
    }

    .btn-gradient-primary {
        background: linear-gradient(45deg, #004085, #0056b3);
        border: none;
        color: #fff;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #003f7f);
        transform: scale(1.05);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-gradient-primary:active {
        transform: scale(1.02);
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    img:hover,
    .placeholder-profile:hover {
        transform: scale(1.1);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    }

    .list-group-item {
        font-size: 1.1rem;
        font-weight: 400;
    }

    .list-group-item i {
        margin-right: 10px;
    }

    .card-footer {
        border-top: 1px solid #e9ecef;
    }

    @media (max-width: 768px) {

        .btn-lg {
            font-size: 1rem;
            padding: 8px 16px;
        }

        .card-header h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
