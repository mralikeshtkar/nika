<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->ip != $request->ip()) $request->user()->update(['ip' => $request->ip()]);
        return $next($request);
    }
}
