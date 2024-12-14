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
        .custom-modal .modal-content {
            background-color: none;
            border: none;
            box-shadow: none;
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
                        <th>Tempat Kegiatan</th>
                        <td>{{ $kegiatan->tempat_kegiatan }}</td>
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
                    @if(Auth::user()->jabatanKegiatanss()->wherePivot('id_kegiatan', $kegiatan->id_kegiatan)->exists())
                    <!-- Tautan Tambah Agenda -->
                    <a href="javascript:void(0);" id="addAgenda" class="btn p-0 border-0 bg-transparent mt-3" 
                    title="Tambah Agenda" 
                    onclick="openModal('{{ route('agenda.create', ['id_kegiatan' => $kegiatan->id_kegiatan]) }}')">
                    <i class="fas fa-plus text-primary"></i>
                </a>
                                    </a>
                @endif                                 
                </h4>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nama Agenda</th>
                            <th>Tempat Agenda</th>
                            <th>Tanggal Agenda</th>
                            <th>Progres</th>
                            @if(Auth::user()->jabatanKegiatans()->exists())
                            <th>Aksi</th>
                            @endif
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
                                    @if(Auth::user()->jabatanKegiatanss()->wherePivot('id_kegiatan', $kegiatan->id_kegiatan)->exists())
                                        <!-- Link Detail -->
                                        <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="openModal('{{ url('/agenda/' . $agenda->id_agenda . '/show') }}')">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <!-- Tombol Edit -->
                                        <a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="openModal('{{ url('/agenda/' . $agenda->id_agenda . '/edit') }}')">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="openModal('{{ url('/agenda/' . $agenda->id_agenda . '/delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endif
                                    <!-- Tombol Upgrade -->
                                    @if(Auth::user()->jabatanKegiatans()->where('is_pic','!=', 1)->exists())
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="openModal('{{ url('detail_agenda/upgrade/' . $kegiatan->id_kegiatan . '/' . $agenda->id_agenda) }}')">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                    @endif
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
<div class="modal fade" id="crudModal" tabindex="-1" aria-labelledby="crudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="modalContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
<script>
     function openModal(url) {
        $('#modalContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
        $('#crudModal').modal('show');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#modalContent').html(response);
            },
            error: function(xhr) {
                $('#modalContent').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat data.</div>');
            }
        });
    }
</script>
</body>
</html>
