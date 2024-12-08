@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex align-items-center w-100">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-link p-0 mr-3" style="font-size: 18px;">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h3 class="card-title mb-0 flex-grow-1">Detail Agenda</h3>
                <div class="card-tools ml-auto">
                    <button onclick="modalAction('{{ route('detail_agenda.create') }}')" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Tambah Progres Agenda
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_detail_agenda">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kegiatan</th>
                        <th>Nama Agenda</th>
                        <th>Progres Agenda</th>
                        <th>Keterangan</th>
                        <th>Berkas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function() {
            $('#table_detail_agenda').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('detail_agenda/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_kegiatan: 1
                    },
                    error: function(xhr, error, code) {
                        console.error("Ajax error: ", error);
                    }
                },
                columns: [
                    { data: "id_detail_agenda" },
                    { data: "kegiatan.nama_kegiatan" }, 
                    { data: "agenda.nama_agenda" },
                    { data: "progres_agenda" },
                    { data: "keterangan" },
                    {
                        data: "berkas", 
                        render: function(data, type, row) {
                            if (data) {
                                return `<div style="text-align: center;"><a href="{{ asset('storage/') }}/${data.replace('storage/', '')}" target="_blank"><i class="fa fa-file-alt" style="font-size: 29px;"></i></a></div>`;
                            } else {
                                return `<div style="text-align: center;">Belum ada berkas yang diunggah.</div>`;
                            }
                        }
                    },
                    { data: "aksi", orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush