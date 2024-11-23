@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Kegiatan</th>
                    <th>Kategori Kegiatan</th>
                    <th>Keterangan</th>
                    <th>Progres Kegiatan</th>
                    <th>Beban Kerja</th>
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

    var dataKegiatan;

    $(document).ready(function() {
        dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('t_detail_kegiatan/list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: "id", className: "", orderable: true, searchable: true },
                { data: "id_kegiatan", className: "", orderable: true, searchable: true },
                { data: "kategori_kegiatan", className: "", orderable: true, searchable: true },
                { data: "keterangan", className: "", orderable: true, searchable: true },
                { data: "progres_kegiatan", className: "", orderable: true, searchable: true },
                { data: "beban_kerja", className: "", orderable: true, searchable: true }
            ]
        });
    });
</script>
@endpush
