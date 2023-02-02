<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrustApp
{
    public function handle(Request $request, Closure $next) {
        if ($request->header('x-api-key') != config('app.apiKey')) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
