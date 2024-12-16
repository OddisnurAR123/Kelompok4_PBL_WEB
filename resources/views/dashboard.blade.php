@extends('layouts.template')

@section('content')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="font-weight-bold" style="color: #8B1A1A;">👋 Selamat datang, {{ Auth::user()->nama_pengguna }}!</h4>
                <p class="text-muted">Lihat informasi terbaru mengenai kegiatan dan agenda Anda di sini.</p>
            </div>

            <div class="row">
                <!-- Kegiatan Terdekat -->
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-header text-white" style="background-color: #F97300;">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-day"></i> Kegiatan Terdekat
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($kegiatan)
                                <div class="text-center">
                                    <h6 class="font-weight-bold text-dark">{{ $kegiatan->nama_kegiatan }}</h6>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y, H:i') }}</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <p class="text-muted">Tidak ada kegiatan terdekat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Agenda Terdekat -->
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-header text-white" style="background-color: #FFB800;">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt"></i> Agenda Terdekat
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($agenda)
                                <div class="text-center">
                                    <h6 class="font-weight-bold text-dark">{{ $agenda->nama_agenda }}</h6>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d M Y, H:i') }}</p>
                                    <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $agenda->tempat_agenda }}</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <p class="text-muted">Tidak ada agenda terdekat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lihat Semua -->
            <div class="text-center mt-4">
                <a href="{{ url('/kegiatan') }}" class="btn btn-lg text-white px-5" style="background-color: #8B1A1A; border-radius: 30px;">
                    <i class="fas fa-list"></i> Lihat Semua Kegiatan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
