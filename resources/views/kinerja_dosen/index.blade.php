@extends('layouts.template')

@section('title', 'Statistik Kinerja Dosen') <!-- Menentukan judul halaman -->

@section('content') <!-- Bagian untuk konten utama -->
    <h1 style="text-align: center;">Statistik Kinerja Dosen</h1>

    <!-- Tabel Kinerja Dosen -->
    <table style="width: 80%; margin: 20px auto; border-collapse: collapse; border: 1px solid #ddd;">
        <thead>
            <tr style="background-color: #f4f4f4;">
                <th style="padding: 10px; text-align: center;">Nama Pengguna</th>
                <th style="padding: 10px; text-align: center;">Jumlah Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosenKegiatan as $dosen)
                <tr>
                    <td style="padding: 10px; text-align: center;">{{ $dosen->nama_pengguna }}</td>
                    <td style="padding: 10px; text-align: center;">{{ $dosen->kegiatan_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Grafik Kinerja Dosen -->
    <div style="width: 80%; margin: 40px auto;">
        <canvas id="chart" width="400" height="200"></canvas>
    </div>
@endsection

@section('scripts') <!-- Bagian untuk skrip tambahan -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($dosenKegiatan->pluck('nama_pengguna')),
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: @json($dosenKegiatan->pluck('kegiatan_count')),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Pengguna'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Kegiatan'
                        }
                    }
                }
            }
        });
    </script>
@endsection
