<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $pengguna = $request->user(); // Gunakan $request->user() untuk mendapatkan pengguna yang terautentikasi.

        if (!$pengguna) {
            abort(403, 'Pengguna tidak ditemukan');
        }

        if ($pengguna->hasRole($role)) {
            return $next($request);
        }

        abort(403, 'Upss!! Kamu tidak memiliki akses ke halaman ini');
    }
}
