@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dashboard Admin - Daftar Dosen dan Kegiatan</h3>
    </div>
    <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Cari Dosen..." onkeyup="searchTable()">
        </div>

        <!-- Table for Dosen and Kegiatan -->
        <table class="table table-striped table-bordered" id="dosenTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dosen</th>
                    <th>Kegiatan yang Diikuti</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedDosen as $key => $users)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $users->first()->nama_pengguna }}</td>
                        <td>
                            <ul class="list-group">
                                @foreach ($users as $user)
                                    <li class="list-group-item">
                                        <i class="fas fa-calendar-alt"></i> {{ $user->nama_kegiatan }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
                @if($groupedDosen->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center">Data dosen dan kegiatan tidak ditemukan</td>
                    </tr>
                @endif
            </tbody>
        </table>
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
