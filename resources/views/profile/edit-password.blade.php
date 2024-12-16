@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header text-center text-white py-4" style="background-color: #01274E;">
            <h2 class="mb-0">Edit Password</h2>
        </div>

        <div class="card-body">
            <!-- Feedback Error -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Edit Password -->
            <form method="POST" action="{{ route('profile.update-password') }}">
                @csrf
                @method('PUT')

                <!-- Password Lama -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" name="current_password" id="current_password" 
                               class="form-control" placeholder="Masukkan password lama" required>
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="new_password" id="new_password" 
                               class="form-control" placeholder="Masukkan password baru" required minlength="8">
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="new_password_confirmation" id="password_confirmation" 
                    class="form-control" placeholder="Konfirmasi password baru" required>
                </div>
                
                <!-- Tombol Submit -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-custom-primary btn-md px-4 py-2 rounded-pill shadow-sm">Simpan Perubahan</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-md px-4 py-2 rounded-pill shadow-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .btn-custom-primary {
        background-color: #01274E;
        border: none;
        color: #fff;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .btn-secondary {
        background-color: #ddd;
        border: none;
        color: #01274E;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .input-group-text {
        background-color: #f8f9fa;
    }

    .alert-danger {
        font-weight: bold;
        text-align: center;
    }
</style>
@endpush

<!-- JavaScript untuk Menghapus Spasi -->
<script>
    document.querySelector('[name="new_password"]').addEventListener('input', function() {
        this.value = this.value.trim();
    });
    document.querySelector('[name="password_confirmation"]').addEventListener('input', function() {
        this.value = this.value.trim();
    });
</script>
