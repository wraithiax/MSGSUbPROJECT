<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $role = session('user_role');

        if ($role === 'user') {
            $role = 'teacher';
        }

        if (! in_array($role, $roles, true)) {
            return redirect()->route('home')->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
