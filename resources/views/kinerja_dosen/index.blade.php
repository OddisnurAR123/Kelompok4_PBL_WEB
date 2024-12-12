@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Statistik Kinerja Dosen</h3>
    </div>
    <div class="card-body">
        <!-- Grafik Gabungan -->
        <div class="chart-container" style="position: relative; height:450px;">
            <canvas id="kinerjaChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .chart-container {
        background-color: linear-gradient(135deg, #e3f2fd, #e1bee7);
        border-radius: 12px;
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
        padding: 25px;
        margin-top: 20px;
    }

    .card-header {
        background: linear-gradient(90deg, #11315F, #11315F);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .card-title {
        font-size: 20px;
        font-weight: bold;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data dari controller
        const chartDataInternal = @json($chartDataInternal);
        const chartDataEksternal = @json($chartDataEksternal);

        // Gabungkan data dari kedua dataset
        const combinedLabels = [...new Set([...chartDataInternal.labels, ...chartDataEksternal.labels])];
        
        const internalData = combinedLabels.map(label => {
            const index = chartDataInternal.labels.indexOf(label);
            return index >= 0 ? chartDataInternal.datasets[0].data[index] : 0;
        });
        
        const externalData = combinedLabels.map(label => {
            const index = chartDataEksternal.labels.indexOf(label);
            return index >= 0 ? chartDataEksternal.datasets[0].data[index] : 0;
        });

        // Debugging untuk memastikan data
        console.log('Combined Labels:', combinedLabels);
        console.log('Internal Data:', internalData);
        console.log('External Data:', externalData);

        // Buat grafik gabungan dengan animasi dan gaya tambahan
        const ctx = document.getElementById('kinerjaChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: combinedLabels,
                datasets: [
                    {
                        label: 'Kegiatan Internal',
                        data: internalData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)', // Ganti warna menjadi lebih segar
                        hoverBackgroundColor: 'rgba(75, 192, 192, 1)', // Hover effect yang lebih kuat
                        borderColor: 'rgba(75, 192, 192, 1)', // Border yang konsisten dengan background
                        borderWidth: 1,
                    },
                    {
                        label: 'Kegiatan Eksternal',
                        data: externalData,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)', // Ganti warna menjadi oranye cerah
                        hoverBackgroundColor: 'rgba(255, 159, 64, 1)', // Hover effect lebih intens
                        borderColor: 'rgba(255, 159, 64, 1)', // Border yang sesuai dengan background
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' Kegiatan';
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Dosen',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Kegiatan',
                        },
                        ticks: {
                            beginAtZero: true,
                        },
                    },
                },
            },
        });
    });
</script>
@endpush
