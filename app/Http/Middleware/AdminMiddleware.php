<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {        
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        return redirect('/')->with('error', 'You do not have access to this page.');
    }
}
