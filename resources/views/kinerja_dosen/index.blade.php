@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Statistik Kinerja Dosen - Tahun {{ $tahun }}</h3>
        <form method="GET" action="{{ route('kinerja_dosen.index') }}" class="float-right">
            <div class="input-group">
                <select name="tahun" class="form-control" onchange="this.form.submit()">
                    @for ($i = date('Y'); $i >= 2000; $i--)
                        <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Grafik Kegiatan Internal -->
            <div class="col-md-6">
                <h4 class="text-center text-internal">Kegiatan Internal</h4>
                <div class="chart-container">
                    <canvas id="kinerjaInternalChart"></canvas>
                </div>
            </div>

            <!-- Grafik Kegiatan Eksternal -->
            <div class="col-md-6">
                <h4 class="text-center text-external">Kegiatan Eksternal</h4>
                <div class="chart-container">
                    <canvas id="kinerjaEksternalChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .chart-container {
        background: #ffffff; /* White background */
        border-radius: 12px;
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
        padding: 25px;
        margin-top: 20px;
    }

    .card-header {
        background: linear-gradient(90deg, #11315F, #11315F); /* Blue to orange */
        color: white;
        border-radius: 8px 8px 0 0;
        text-align: center;
    }

    .card-title {
        font-size: 24px;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 1px;
    }

    h4 {
        font-weight: bold;
        font-size: 20px;
        color: #800000; /* Dark red */
        text-transform: uppercase;
    }

    .text-internal {
        color: #8B1A1A; /* Internal text color */
    }

    .text-external {
        color: #F28C28; /* External text color */
    }

    select.form-control {
        background: #f3f4f6;
        border: 1px solid #ccc;
        font-size: 14px;
        border-radius: 8px;
        height: 38px; /* Sesuaikan tinggi dropdown */
    }

    button.btn-primary {
        background: #FF8C00;
        border-color: #FF8C00;
        font-weight: bold;
        text-transform: uppercase;
        height: 38px; /* Samakan tinggi tombol dengan dropdown */
        line-height: 1.2; /* Perbaiki align text */
        padding: 0 15px; /* Sesuaikan padding horizontal */
        font-size: 14px; /* Konsistensi ukuran font */
    }

    button.btn-primary:hover {
        background: #F28C28;
        border-color: #F28C28;
    }

    .input-group .form-control {
        border-radius: 8px 0 0 8px; /* Round left edge */
    }

    .input-group-append .btn {
        border-radius: 0 8px 8px 0; /* Round right edge */
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Grafik Kegiatan Internal
    const chartLabelsInternal = @json($chartLabelsInternal);
    const chartDataInternal = @json($chartDataInternal);

    const ctxInternal = document.getElementById('kinerjaInternalChart').getContext('2d');
    new Chart(ctxInternal, {
        type: 'bar',
        data: {
            labels: chartLabelsInternal,
            datasets: [{
                label: 'Total Kegiatan Internal',
                data: chartDataInternal,
                backgroundColor: '#8B1A1A',
                borderColor: '#800000',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Grafik Kegiatan Eksternal
    const chartLabelsEksternal = @json($chartLabelsEksternal);
    const chartDataEksternal = @json($chartDataEksternal);

    const ctxEksternal = document.getElementById('kinerjaEksternalChart').getContext('2d');
    new Chart(ctxEksternal, {
        type: 'bar',
        data: {
            labels: chartLabelsEksternal,
            datasets: [{
                label: 'Total Kegiatan Eksternal',
                data: chartDataEksternal,
                backgroundColor: '#F28C28',
                borderColor: '#FF8C00',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
