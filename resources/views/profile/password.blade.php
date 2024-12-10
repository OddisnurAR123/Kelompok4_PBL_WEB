@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ganti Password</h1>

        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current_password">Password Lama</label>
                <input type="password" class="form-control" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" name="new_password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-success">Ganti Password</button>
        </form>
    </div>
@endsection
