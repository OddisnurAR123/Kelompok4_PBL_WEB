@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Ganti Password</h2>
    <<form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PUT')
    
        <div class="form-group mb-3">
            <label for="current_password">Password Saat Ini</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
    
        <div class="form-group mb-3">
            <label for="new_password">Password Baru</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
    
        <div class="form-group mb-3">
            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>
    
        <button type="submit" class="btn btn-success">Update Password</button>
    </form>    
</div>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@endsection
