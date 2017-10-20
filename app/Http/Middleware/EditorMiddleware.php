<?php

namespace App\Http\Middleware;

use Closure;

class EditorMiddleware
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
        $roles = array('admin', 'moderator');

        if ($request->user() === null || !in_array($request->user()->role, $roles ))
        {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
