<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to login and home routes without session
        if ($request->routeIs('login', 'login.submit')) {
            return $next($request);
        }

        // Check if user is logged in
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $user = User::find(Session::get('user_id'));

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Your session is no longer valid. Please log in again.');
        }

        Session::put('user', $user);
        Session::put('user_email', $user->email);
        Session::put('user_role', $user->normalizedRole());
        Session::put('force_password_change', $user->force_password_change);

        if (
            $user->force_password_change &&
            !$request->routeIs('home', 'logout', 'dashboard.password.update', 'profile.edit', 'profile.update')
        ) {
            return redirect()->route('home')->with('error', 'Please change your password first before accessing other pages.');
        }

        // Add headers to prevent page caching after logout
        $response = $next($request);
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        return $response;
    }
}
