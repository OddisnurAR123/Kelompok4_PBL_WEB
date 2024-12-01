@extends('layouts.template')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        {{-- <div class="card-header" style="background-color: #8B1A1A; color: white;">
            <h3 class="card-title">👋 Halo, apa kabar!!!</h3>
        </div> --}}
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="font-weight-bold" style="color: #8B1A1A;">Selamat datang di halaman utama aplikasi ini!</h4>
                <p class="text-muted">Kami menyediakan informasi kegiatan dan agenda terdekat untuk Anda agar tidak ketinggalan informasi penting.</p>
            </div>

            <div class="row">
                <!-- Kegiatan Terdekat -->
                <div class="col-md-6 mb-3">
                    <div class="card border-info shadow-sm">
                        <div class="card-header" style="background-color: #F97300; color: white;">
                            <h5 class="mb-0">📅 Kegiatan Terdekat</h5>
                        </div>
                        <div class="card-body">
                            @if($kegiatan)
                                <p class="font-weight-bold text-dark">Nama Kegiatan:</p>
                                <p>{{ $kegiatan->nama_kegiatan }}</p>
                                <p class="font-weight-bold text-dark">Tanggal Mulai:</p>
                                <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</p>
                                <p class="font-weight-bold text-dark">Tanggal Selesai:</p>
                                <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}</p>
                            @else
                                <p class="text-muted">Tidak ada kegiatan terdekat.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Agenda Terdekat -->
                <div class="col-md-6 mb-3">
                    <div class="card border-success shadow-sm">
                        <div class="card-header" style="background-color: #FFB800; color: white;">
                            <h5 class="mb-0">🗓️ Agenda Terdekat</h5>
                        </div>
                        <div class="card-body">
                            @if($agenda)
                                <p class="font-weight-bold text-dark">Nama Agenda:</p>
                                <p>{{ $agenda->nama_agenda }}</p>
                                <p class="font-weight-bold text-dark">Tanggal Agenda:</p>
                                <p>{{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d M Y') }}</p>
                                <p class="font-weight-bold text-dark">Tempat:</p>
                                <p>{{ $agenda->tempat_agenda }}</p>
                            @else
                                <p class="text-muted">Tidak ada agenda terdekat.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <!-- Tombol Lihat Semua Kegiatan -->
                <div class="col-md-6 mb-3">
                    <a href="{{ url('/detail_kegiatan') }}" class="btn btn-lg btn-block" style="background-color: #8B1A1A; color: white; border-radius: 30px;">
                        <i class="fas fa-list"></i> Lihat Semua Kegiatan
                    </a>
                </div>
                
                <!-- Tombol Lihat Semua Agenda -->
                <div class="col-md-6 mb-3">
                    <a href="{{ url('/agenda') }}" class="btn btn-lg btn-block" style="background-color: #F97300; color: white; border-radius: 30px;">
                        <i class="fas fa-calendar-day"></i> Lihat Semua Agenda
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection
