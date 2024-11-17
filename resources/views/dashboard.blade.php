@extends('layouts.template')

@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">👋 Halo, apa kabar!!!</h3>
            <div class="card-tools">
                <small class="text-light">Selamat datang di aplikasi kami</small>
            </div>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <h5 class="font-weight-bold">Selamat datang di halaman utama aplikasi ini</h5>
                <p class="text-muted">Kami menyediakan informasi kegiatan dan agenda terdekat untuk Anda!</p>
            </div>

            <div class="row">
                <!-- Kegiatan Terdekat -->
                <div class="col-md-6">
                    <div class="card border-info mb-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">📅 Kegiatan Terdekat</h5>
                        </div>
                        <div class="card-body">
                            @if($kegiatan)
                                <p><strong>Nama Kegiatan:</strong> {{ $kegiatan->nama_kegiatan }}</p>
                                <p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</p>
                                <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}</p>
                            @else
                                <p class="text-muted">Tidak ada kegiatan terdekat.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Agenda Terdekat -->
                <div class="col-md-6">
                    <div class="card border-success mb-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">🗓️ Agenda Terdekat</h5>
                        </div>
                        <div class="card-body">
                            @if($agenda)
                                <p><strong>Nama Agenda:</strong> {{ $agenda->nama_agenda }}</p>
                                <p><strong>Tanggal Agenda:</strong> {{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d M Y') }}</p>
                                <p><strong>Tempat:</strong> {{ $agenda->tempat_agenda }}</p>
                            @else
                                <p class="text-muted">Tidak ada agenda terdekat.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <a href="{{ url('/kegiatan') }}" class="btn btn-primary mx-2 nav-link ">Lihat Semua Kegiatan</a>
                <a href="{{ url('/agenda') }}" class="btn btn-success mx-2 nav-link ">Lihat Semua Agenda</a>
            </div>            
        </div>
    </div>
</div>

@endsection
