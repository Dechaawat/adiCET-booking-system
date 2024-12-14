<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // ตรวจสอบว่าผู้ใช้เป็น Admin หรือไม่
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redirect หากผู้ใช้ไม่ใช่ Admin
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}