@extends('layouts.template')

@section('content')
<div class="container">
    @if(isset($breadcrumb))
        <h1>{{ $breadcrumb->title }}</h1>
        <ol>
            @foreach ($breadcrumb->list as $item)
                @if(is_object($item))
                    <li><a href="{{ $item->url }}">{{ $item->label }}</a></li>
                @else
                    <li>{{ $item }}</li>
                @endif
            @endforeach
        </ol>
    @else
        <p>Breadcrumb tidak tersedia.</p>
    @endif

    <div class="card">
        <div class="card-header">Profil</div>
        <div class="card-body">
            <p><strong>Nama Pengguna:</strong> {{ $user->nama_pengguna }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            
            <p>
                <strong>Foto Profil:</strong><br>
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" width="100">
                @else
                    <i>Tidak ada foto</i>
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
