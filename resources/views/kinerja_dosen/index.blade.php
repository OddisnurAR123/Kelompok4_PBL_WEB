@extends('layouts.template')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Kinerja Pengguna</title>
    <!-- Tambahkan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
</head>
<body>
    <h1>Statistik Kinerja Pengguna</h1>

    <!-- Grafik -->
    <canvas id="kinerjaChart" width="800" height="400"></canvas>
    <script>
        const ctx = document.getElementById('kinerjaChart').getContext('2d');
        const kinerjaChart = new Chart(ctx, {
            type: 'bar', // Tipe grafik
            data: {
                labels: @json($chartData['labels']), // Nama pengguna
                datasets: @json($chartData['datasets']), // Data jumlah kegiatan
            },
            options: {
                responsive: true,
                indexAxis: 'y', // Grafik horizontal
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Jumlah Kegiatan', // Label untuk sumbu X
                        },
                        beginAtZero: true,
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Nama Pengguna', // Label untuk sumbu Y
                        },
                    },
                },
            },
        });
    </script>
</body>
</html>
