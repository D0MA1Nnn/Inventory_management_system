<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return redirect('/login');

        if (auth()->user()->role !== 'staff') {
            return redirect('/')->with('error', 'Staff only');
        }

        return $next($request);
    }
}