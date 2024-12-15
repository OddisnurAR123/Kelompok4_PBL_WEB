@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Kegiatan</h3>       
    </div>      
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Filter berdasarkan periode -->
        <div class="d-flex justify-content-between">
            <div class="form-group">
                <label for="periode_filter">Periode</label>
                <select class="form-control" id="periode_filter">
                    <!-- Pilihan periode akan diisi oleh JavaScript -->
                </select>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab Daftar Kegiatan -->
            <div class="tab-pane fade show active" id="daftar" role="tabpanel" aria-labelledby="daftar-tab">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Grafik -->
<div id="progressModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grafik Progress Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="progressChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function loadProgressChart(id_kegiatan) {
        // Request data progress dari server
        $.ajax({
            url: `{{ url('kegiatan/progress') }}/${id_kegiatan}`,
            type: 'GET',
            success: function(data) {
                // Inisialisasi Chart.js dengan data
                const ctx = document.getElementById('progressChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Belum Selesai'],
                        datasets: [{
                            data: [data.selesai, data.belum_selesai],
                            backgroundColor: ['#28a745', '#dc3545'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        }
                    }
                });
                // Tampilkan modal
                $('#progressModal').modal('show');
            },
            error: function() {
                alert('Gagal memuat data progress.');
            }
        });
    }

    $(document).ready(function() {
        // Menghitung 5 tahun terakhir
        var currentYear = new Date().getFullYear();
        var years = [];
        for (var i = currentYear; i > currentYear - 5; i--) {
            years.push(i);
        }

        // Menambahkan opsi tahun ke dalam dropdown
        var periodeFilter = $('#periode_filter');
        periodeFilter.empty(); // Menghapus opsi yang ada sebelumnya
        periodeFilter.append('<option value="">Semua Periode</option>'); // Opsi untuk semua periode
        years.forEach(function(year) {
            periodeFilter.append('<option value="' + year + '">' + year + '</option>');
        });

        var dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('kegiatan/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.periode_filter = $('#periode_filter').val();
                }
            },
            columns: [
                { data: "id_kegiatan" },
                { data: "nama_kegiatan" },
                { data: "tanggal_mulai" },
                { data: "tanggal_selesai" },
                { data: "periode" },
                { data: "status" },
                {
                    data: "id_kegiatan",
                    render: function(data) {
                        return `<button class="btn btn-primary btn-sm" onclick="loadProgressChart(${data})">Lihat Grafik</button>`;
                    }
                }
            ]
        });

        // Event listener untuk filter periode
        $('#periode_filter').change(function() {
            dataKegiatan.draw(); // Reload table dengan filter yang baru
        });
    });
</script>
@endpush
