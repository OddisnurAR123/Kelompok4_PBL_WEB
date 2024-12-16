@extends('layouts.template')

@section('content')
<style>
    /* Menghilangkan scroll pada halaman */
    html, body {
        overflow: hidden;
        height: 100%;
        margin: 0;
    }

    /* Membatasi tinggi tabel */
    .table-wrapper {
        max-height: 90vh; /* Pastikan tabel tetap berada di dalam viewport */
        overflow-y: auto; /* Hanya scrollbar untuk tabel jika data terlalu banyak */
    }

    /* Sesuaikan ukuran font agar tabel lebih kompak */
    table {
        font-size: 14px; /* Perkecil ukuran font */
    }

    th, td {
        padding: 5px; /* Kurangi padding untuk menghemat ruang */
        text-align: left;
    }
</style>

<div class="card shadow">
    <div class="card-body">
        <!-- Judul -->
        <h4 class="mb-4 text-center">Rekapitulasi Kegiatan Dosen</h4>

        <!-- Table for Dosen and Kegiatan -->
        <div class="table-wrapper">
            <table class="table table-hover table-striped table-bordered" id="dosenTable" style="table-layout: fixed; width: 100%;">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama Dosen</th>
                        <th style="width: 35%;">Kegiatan yang Diikuti</th>
                        <th style="width: 35%;">Jabatan di Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedDosen as $key => $users)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $users->first()->nama_pengguna }}</td>
                            <td>
                                <ul class="list-group list-unstyled">
                                    @foreach ($users as $user)
                                        <li class="mb-2">
                                            <i class="fas fa-calendar-alt text-primary mr-2"></i> {{ $user->nama_kegiatan }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-group list-unstyled">
                                    @foreach ($users as $user)
                                        <li class="mb-2">
                                            <i class="fas fa-user-tag text-success mr-2"></i> {{ $user->nama_jabatan_kegiatan ?? '-' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    @if($groupedDosen->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Data dosen dan kegiatan tidak ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function searchTable() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toUpperCase();
        let table = document.getElementById('dosenTable');
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td')[1]; // Nama Dosen
            if (td) {
                let txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }       
        }
    }
</script>
@endsection
