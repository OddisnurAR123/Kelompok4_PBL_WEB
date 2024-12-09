<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();

        if (!$user || $user->jenis_pengguna !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
