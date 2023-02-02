<?php

namespace App\Http\Middleware;

use App\Exceptions\AssemblerException;
use App\Exceptions\AssemblerExceptionInterface;
use Closure;
use Illuminate\Http\Request;

class IsOnlineMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->is_online) {
            throw new AssemblerException(AssemblerExceptionInterface::OFFLINE);
        }

        return $next($request);
    }
}
