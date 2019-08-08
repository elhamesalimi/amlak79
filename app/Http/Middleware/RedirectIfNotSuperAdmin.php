<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        $user = Auth::guard($guard)->user();
        if ( $user->role !== 'super_admin' ) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
