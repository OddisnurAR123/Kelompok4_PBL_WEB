@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="font-weight-bold text-danger">Selamat datang, {{ Auth::user()->nama_pengguna }}!</h4>
                <p class="text-muted">Berikut informasi kegiatan dan agenda Anda:</p>
            </div>

            <!-- Bagian Notifikasi -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-bell mr-2"></i>
                    <h5 class="mb-0">Notifikasi</h5>
                </div>
                <div class="card-body">
                    @if(isset($notifications) && $notifications->isNotEmpty())
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">
                                    <p>{{ $notification->data['message'] }}</p>
                                    <small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y H:i') }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Tidak ada notifikasi baru.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bagian Reminder -->
            <div class="card">
                <div class="card-header bg-warning text-white d-flex align-items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <h5 class="mb-0">Reminder Kegiatan dan Agenda (1 Minggu ke Depan)</h5>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold text-dark">Kegiatan Terdekat:</h6>
                    @if(isset($reminderKegiatan) && $reminderKegiatan->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach($reminderKegiatan as $kegiatan)
                                <li class="mb-3">
                                    <strong>{{ $kegiatan->nama_kegiatan }}</strong>
                                    <p class="mb-0">Tanggal: {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Tidak ada kegiatan dalam 1 minggu ke depan.</p>
                    @endif

                    <h6 class="font-weight-bold text-dark mt-4">Agenda Terdekat:</h6>
                    @if(isset($reminderAgenda) && $reminderAgenda->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach($reminderAgenda as $agenda)
                                <li class="mb-3">
                                    <strong>{{ $agenda->nama_agenda }}</strong>
                                    <p class="mb-0">Tanggal: {{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d M Y') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Tidak ada agenda dalam 1 minggu ke depan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
