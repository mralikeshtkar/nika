<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use App\Responses\Api\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInactiveUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && UserStatus::Inactive()->is($request->user()->status)) return ApiResponse::error(trans("Your account is inactive"),Response::HTTP_BAD_REQUEST)->send();
        return $next($request);
    }
}
