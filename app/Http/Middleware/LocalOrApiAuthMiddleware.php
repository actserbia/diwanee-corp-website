<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class LocalOrApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ip() !== '127.0.0.1' && Auth::guard('api')->user() === null) {
            abort(403, 'Unauthorized action.');
            return redirect('/');
        }

        return $next($request);
    }
}
