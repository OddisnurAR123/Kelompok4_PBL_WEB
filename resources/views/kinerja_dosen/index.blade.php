@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Statistik Kinerja Dosen</h3>
    </div>
    <div class="card-body">
        <!-- Tambahkan Grafik -->
        <div class="chart-container" style="position: relative; height:400px;">
            <canvas id="kinerjaChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* Styling untuk membuat grafik lebih menarik */
    .chart-container {
        margin-top: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil data dari controller
        const chartData = @json($chartData);

        const ctx = document.getElementById('kinerjaChart').getContext('2d');
        const kinerjaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Untuk menjaga rasio tampilan grafik responsif
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' Kegiatan';
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Dosen',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Kegiatan',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                },
                animation: {
                    duration: 1500,  // Durasi animasi untuk tampilan pertama grafik
                    easing: 'easeOutBounce'
                },
            },
        });
    });
</script>
@endpush
