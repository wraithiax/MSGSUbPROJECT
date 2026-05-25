<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Schema;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow admin maintenance routes to bypass maintenance check
        if ($request->is('admin/maintenance*')) {
            return $next($request);
        }

        if (! Schema::hasTable('maintenances')) {
            return $next($request);
        }

        // Check if there's active maintenance
        $maintenance = Maintenance::where('status', 'active')->first();

        if ($maintenance) {
            return response()->view('maintenance', [
                'maintenance' => $maintenance
            ], 503);
        }

        return $next($request);
    }
}
