<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom middleware pengecek role.
 * Pemakaian di route:  ->middleware('role:admin')   atau   ->middleware('role:admin,editor')
 * (alias 'role' didaftarkan di AppServiceProvider)
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Belum login, atau role tidak termasuk yang diizinkan -> 403 Forbidden
        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Role kamu tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
