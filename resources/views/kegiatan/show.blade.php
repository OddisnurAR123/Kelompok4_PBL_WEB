<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <style>
        /* Keyframes for Shake animation */
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(5px); }
        }

        .animated-detail {
            animation: shake 0.5s ease-in-out;
            animation-iteration-count: 1;
            background-color: #f0f8ff;
        }

        .card-header {
            background-color: #01274E;
        }

        .card-title {
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detail Kegiatan</h3>
        </div>
        <div class="card-body animated-detail">
            @if(!$kegiatan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Kegiatan</th>
                        <td>{{ $kegiatan->id_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Kode Kegiatan</th>
                        <td>{{ $kegiatan->kode_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ $kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ $kegiatan->tanggal_selesai ? \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $kegiatan->periode }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Kegiatan</th>
                        <td>{{ $kegiatan->kategoriKegiatan->nama_kategori_kegiatan ?? 'Tidak ada kategori' }}</td>
                    </tr>
                    <tr>
                        <th>Progres Kegiatan</th>
                        <td>
                            @if($kegiatan->detailKegiatan && $kegiatan->detailKegiatan->count())
                                @foreach($kegiatan->detailKegiatan as $detail)
                                    <p>{{ $detail->progres_kegiatan }}%</p>
                                @endforeach
                            @else
                                Tidak ada progres.
                            @endif
                        </td>
                    </tr>                    
                </table>

                <h4 class="mt-4">Anggota Kegiatan</h4>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan->anggota as $anggota)
                            <tr>
                                <td>{{ $anggota->nama_pengguna }}</td>
                                <td>{{ $anggota->nama_jabatan_kegiatan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Tidak ada anggota kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h4 class="mt-4 d-flex justify-content-between">
                    Agenda Kegiatan
                    <!-- Tautan Tambah Agenda -->
                    <a href="{{ route('agenda.index') }}" id="addAnggota" class="btn p-0 border-0 bg-transparent mt-3" title="Tambah Agenda">
                        <i class="fas fa-plus text-primary"></i>
                    </a>                    
                </h4>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nama Agenda</th>
                            <th>Tempat Agenda</th>
                            <th>Tanggal Agenda</th>
                            <th>Progres</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan->agenda as $agenda)
                            <tr>
                                <td>{{ $agenda->nama_agenda }}</td>
                                <td>{{ $agenda->tempat_agenda }}</td>
                                <td>{{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('d-m-Y H:i') }}</td>
                                <td>
                                    @if($agenda->detailAgenda && $agenda->detailAgenda->count())
                                        @foreach($agenda->detailAgenda as $detail)
                                            <p>{{ $detail->progres_agenda }}%</p>
                                        @endforeach
                                    @else
                                        Tidak ada progres.
                                    @endif
                                </td>
                                <td>
                                    <!-- Link Detail -->
                                    <a 
                                        href="{{ url('/agenda/' . $agenda->id_agenda . '/show') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a 
                                        href="{{ url('/agenda/' . $agenda->id_agenda . '/edit') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <a 
                                        href="{{ url('/agenda/' . $agenda->id_agenda . '/delete') }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> 
                                    </a>
                                    <!-- Tombol Upgrade -->
                                    <a href="{{ route('detail_agenda.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                </td>                               
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Tidak ada agenda kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>                
            @endif
            <div class="text-right">
                <a href="{{ url('kegiatan') }}" class="btn btn-warning btn-sm mt-2">Tutup</a>
            </div>            
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

</body>
</html>