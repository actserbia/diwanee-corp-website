<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        if ($request->user() === null || $request->user()->role != 'admin')
        {
            abort(403, 'Unauthorized action.');
            return redirect('/');
        }

        return $next($request);
    }
}
