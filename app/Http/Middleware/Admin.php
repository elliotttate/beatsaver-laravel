<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Checks if the authorized user is an admin.
     * Sends a 404 response to preserve security
     * and to keep the admin panel routes hidden.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->admin) {
            return $next($request);
        }

        return abort(404);
    }
}
