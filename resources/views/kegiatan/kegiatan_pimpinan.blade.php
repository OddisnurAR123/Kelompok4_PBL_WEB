@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <!-- Header Section -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Kegiatan</h3>     
    </div>      

    <!-- Body Section -->
    <div class="card-body">
        <!-- Success & Error Alerts -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Filter Section -->
        <div class="d-flex justify-content-between">
            <div class="form-group">
                <label for="periode_filter">Periode</label>
                <select class="form-control" id="periode_filter">
                    <!-- Options will be dynamically populated via JavaScript -->
                </select>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="daftar-tab" data-bs-toggle="tab" href="#daftar" role="tab" aria-controls="daftar" aria-selected="true">Daftar Kegiatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="eksternal-tab" data-bs-toggle="tab" href="{{ route('kegiatan_eksternal.index') }}" role="tab" aria-controls="eksternal" aria-selected="false">Kegiatan Eksternal</a>
            </li>            
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab Daftar Kegiatan -->
            <div class="tab-pane fade show active" id="daftar" role="tabpanel" aria-labelledby="daftar-tab">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                    <thead>
                        <tr>
                            <th>Nama Kegiatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be dynamically populated -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
<style>
    .table th {
        background-color: #01274E;
        color: #f8f9fa;
    }
</style>
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
        // Populate the filter with the last 5 years
        var currentYear = new Date().getFullYear();
        var periodeFilter = $('#periode_filter');
        
        for (var year = currentYear; year >= currentYear - 4; year--) {
            periodeFilter.append(`<option value="${year}">${year}</option>`);
        }

        // Initialize the DataTable
        dataKegiatan = $('#table_kegiatan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url('/kegiatan_pimpinan/list') }}',
                type: 'POST',
                data: function(d) {
                    d.periode = $('#periode_filter').val();
                    d._token = '{{ csrf_token() }}';
                }
            },
            columns: [
                { data: 'nama_kegiatan', name: 'nama_kegiatan' },
                { data: 'status', name: 'status' },
            ],
        });

        // Reload DataTable when the filter is changed
        $('#periode_filter').on('change', function() {
            dataKegiatan.ajax.reload();
        });
    });
</script>
@endpush
