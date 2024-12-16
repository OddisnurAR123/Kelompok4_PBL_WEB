@extends('layouts.template')

@section('content')
<div class="card shadow">
    <div class="card-body">

        <!-- Table for Dosen and Kegiatan -->
        <table class="table table-hover table-striped table-bordered" id="dosenTable">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Dosen</th>
                    <th>Kegiatan yang Diikuti</th>
                    <th>Jabatan di Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedDosen as $key => $users)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $users->first()->nama_pengguna }}</td>
                        <td>
                            <ul class="list-group">
                                @foreach ($users as $user)
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-primary mr-2"></i> {{ $user->nama_kegiatan }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group">
                                @foreach ($users as $user)
                                    <li class="list-group-item d-flex align-items-center">
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
