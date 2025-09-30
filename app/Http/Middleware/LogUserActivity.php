<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $userId = optional($request->user())->id ?? 'guest';
        $endpoint = $request->path();
        $timestamp = now()->toDateTimeString();

        Log::info('User Activity', [
            'user_id' => $userId,
            'endpoint' => $endpoint,
            'timestamp' => $timestamp,
        ]);

        return $response;
    }
}
