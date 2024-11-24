<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $user = Auth::user();

       if($user && $user->role === 'admin') {
        return $next($request);
       }

       $allowedRoutes = ['dashboard', 'projects'];

       if(in_array($request->route()->getName(), $allowedRoutes)) {
        return $next($request);
       }

       return redirect()->route('dashboard')->with('error', 'Access Denied');
    }
}
