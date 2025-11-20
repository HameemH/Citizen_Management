<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCitizenVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->verification_status !== 'verified') {
            return redirect()->route('citizen.dashboard')
                ->with('status', 'Please complete verification before using property services.');
        }

        return $next($request);
    }
}