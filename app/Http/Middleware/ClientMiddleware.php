<?php

namespace App\Http\Middleware;

use Closure;

class ClientMiddleware
{

    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
