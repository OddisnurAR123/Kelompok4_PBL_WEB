@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="font-weight-bold" style="color: #8B1A1A;">Selamat datang, {{ Auth::user()->nama_pengguna }}!</h4>
                <p class="text-muted">Berikut informasi notifikasi Anda:</p>
            </div>

            <!-- Bagian Notifikasi -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>🔔 Notifikasi</h5>
                </div>
                <div class="card-body">
                    @if(isset($notifikasi) && count($notifikasi) > 0)
                        <ul>
                            @foreach($notifikasi as $notif)
                                <li>{{ $notif }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada notifikasi untuk ditampilkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
