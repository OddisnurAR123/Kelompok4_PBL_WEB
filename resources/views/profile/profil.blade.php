@extends('layouts.template')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header text-center bg-gradient-primary text-white py-4">
                <h2 class="mb-0">Profil Saya</h2>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Foto Profil -->
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="mb-3 position-relative">
                            @if ($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                                     alt="Foto Profil" 
                                     class="rounded-circle img-fluid shadow-lg" 
                                     style="width: 180px; height: 180px; object-fit: cover; transition: transform 0.3s;">
                            @else
                                <div class="placeholder-profile bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle shadow-lg" 
                                     style="width: 180px; height: 180px; font-size: 60px; transition: transform 0.3s;">
                                    ?
                                </div>
                                <p class="mt-2 text-muted">Foto belum diupload</p>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Profil -->
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
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-lock text-primary"></i> 
                                    <strong>Password:</strong> ****** 
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center bg-light">
                <a href="{{ route('profile.edit') }}" class="btn btn-gradient-primary btn-lg px-5 py-2">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    /* Gradasi Warna */
    .bg-gradient-primary {
        background: linear-gradient(45deg, #11315F, #315F81);
    }

    .btn-gradient-primary {
        background: linear-gradient(45deg, #11315F, #315F81);
        border: none;
        color: #fff;
        transition: transform 0.2s;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #315F81, #11315F);
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    /* Efek Hover untuk Foto */
    img:hover,
    .placeholder-profile:hover {
        transform: scale(1.1);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Tata Letak yang Rapi */
    .list-group-item {
        font-size: 1.2rem;
        font-weight: 500;
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
