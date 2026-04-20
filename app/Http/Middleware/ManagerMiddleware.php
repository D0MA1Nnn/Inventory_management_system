<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return redirect('/login');

        if (auth()->user()->role !== 'manager') {
            return redirect('/')->with('error', 'Manager only');
        }

        return $next($request);
    }
}