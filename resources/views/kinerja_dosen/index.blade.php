@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Statistik Kinerja Dosen</h3>
        <!-- Dropdown untuk memilih periode -->
        <select id="periodeDropdown" class="form-control w-auto">
            <option value="all">Semua Periode</option>
            @foreach($availablePeriods as $period)
                <option value="{{ $period }}">{{ $period }}</option>
            @endforeach
        </select>
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
        background: linear-gradient(135deg, #e3f2fd, #e1bee7);
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
        const allPeriods = @json($availablePeriods);

        let filteredInternalData = chartDataInternal;
        let filteredEksternalData = chartDataEksternal;

        // Fungsi untuk memfilter data berdasarkan periode
        function filterDataByPeriod(period) {
            if (period === 'all') {
                filteredInternalData = chartDataInternal;
                filteredEksternalData = chartDataEksternal;
            } else {
                filteredInternalData = {
                    labels: chartDataInternal.labels.filter((_, index) => chartDataInternal.periode[index] === period),
                    datasets: [{
                        data: chartDataInternal.datasets[0].data.filter((_, index) => chartDataInternal.periode[index] === period),
                    }],
                };

                filteredEksternalData = {
                    labels: chartDataEksternal.labels.filter((_, index) => chartDataEksternal.periode[index] === period),
                    datasets: [{
                        data: chartDataEksternal.datasets[0].data.filter((_, index) => chartDataEksternal.periode[index] === period),
                    }],
                };
            }
            updateChart();
        }

        // Fungsi untuk memperbarui grafik
        function updateChart() {
            const combinedLabels = [...new Set([...filteredInternalData.labels, ...filteredEksternalData.labels])];

            const internalData = combinedLabels.map(label => {
                const index = filteredInternalData.labels.indexOf(label);
                return index >= 0 ? filteredInternalData.datasets[0].data[index] : 0;
            });

            const externalData = combinedLabels.map(label => {
                const index = filteredEksternalData.labels.indexOf(label);
                return index >= 0 ? filteredEksternalData.datasets[0].data[index] : 0;
            });

            chart.data.labels = combinedLabels;
            chart.data.datasets[0].data = internalData;
            chart.data.datasets[1].data = externalData;
            chart.update();
        }

        // Inisialisasi grafik
        const ctx = document.getElementById('kinerjaChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Kegiatan Internal',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        hoverBackgroundColor: 'rgba(75, 192, 192, 1)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Kegiatan Eksternal',
                        data: [],
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        hoverBackgroundColor: 'rgba(255, 159, 64, 1)',
                        borderColor: 'rgba(255, 159, 64, 1)',
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
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
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

        // Event listener untuk dropdown periode
        document.getElementById('periodeDropdown').addEventListener('change', function (event) {
            const selectedPeriod = event.target.value;
            filterDataByPeriod(selectedPeriod);
        });

        // Inisialisasi grafik dengan data default (periode pertama)
        filterDataByPeriod('all');
    });
</script>
@endpush
