<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LogActivityMiddleware
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
        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'method' => $request->method(),
                'url' => $request->url(),
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'input' => $request->except(['password', 'password_confirmation'])
            ])
            ->log('Request made to ' . $request->url());

        // Continue processing the request
        return $next($request);
    }
}
