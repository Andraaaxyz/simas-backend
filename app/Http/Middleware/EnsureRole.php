<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowed = array_map(
            fn ($role) => Role::from($role)->value,
            $roles
        );

        $userRole = $request->user()?->role?->nama_role;

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini');
        }

        return $next($request);
    }
}
