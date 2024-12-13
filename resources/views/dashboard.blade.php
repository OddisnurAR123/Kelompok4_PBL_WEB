@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="font-weight-bold" style="color: #8B1A1A;">Selamat datang, {{ Auth::user()->nama_pengguna }}!</h4>
                <p class="text-muted">Berikut informasi kegiatan dan agenda Anda:</p>
            </div>

            <div class="row">
                <!-- Kegiatan Terdekat -->
                <div class="col-md-6 mb-3">
                    <div class="card border-info shadow-sm">
                        <div class="card-header" style="background-color: #F97300; color: white;">
                            <h5 class="mb-0">📅 Kegiatan Terdekat</h5>
                        </div>
                        <div class="card-body text-center">
                            @if(isset($newKegiatan) && $newKegiatan->isNotEmpty())
                                @foreach($newKegiatan as $kegiatan)
                                    <p class="font-weight-bold text-dark">Nama Kegiatan:</p>
                                    <p>{{ $kegiatan->nama_kegiatan }}</p>
                                    <p class="font-weight-bold text-dark">Tanggal:</p>
                                    <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</p>
                                @endforeach
                            @else
                                <p class="text-muted">Anda belum memiliki kegiatan terdekat.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Agenda Terdekat -->
                <div class="col-md-6 mb-3">
                    <div class="card border-success shadow-sm">
                        <div class="card-header" style="background-color: #FFB800; color: white;">
                            <h5 class="mb-0">🗓 Agenda Terdekat</h5>
                        </div>
                        <div class="card-body text-center">
                            @if(isset($newAgenda) && $newAgenda->isNotEmpty())
                                @foreach($newAgenda as $agenda)
                                    <p class="font-weight-bold text-dark">Nama Agenda:</p>
                                    <p>{{ $agenda->nama_agenda }}</p>
                                    <p class="font-weight-bold text-dark">Tanggal:</p>
                                    <p>{{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d M Y') }}</p>
                                @endforeach
                            @else
                                <p class="text-muted">Anda belum memiliki agenda terdekat.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <!-- Tombol Lihat Semua Kegiatan -->
                <a href="{{ url('/kegiatan') }}" class="btn btn-lg mr-3" style="background-color: #8B1A1A; color: white; border-radius: 30px;">
                    <i class="fas fa-list"></i> Lihat Semua Kegiatan
                </a>

                <!-- Tombol Lihat Semua Agenda -->
                <a href="{{ url('/agenda') }}" class="btn btn-lg" style="background-color: #F97300; color: white; border-radius: 30px;">
                    <i class="fas fa-calendar-day"></i> Lihat Semua Agenda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection