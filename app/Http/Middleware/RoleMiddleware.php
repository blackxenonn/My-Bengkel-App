<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        if (!$user || !$user->role) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Owner memiliki akses ke seluruh fitur.
        if ($user->role->name === 'owner') {
            return $next($request);
        }

        // Periksa akses untuk role lainnya.
        if (!in_array($user->role->name, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
