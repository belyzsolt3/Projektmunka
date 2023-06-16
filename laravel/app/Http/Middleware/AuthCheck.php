<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session()->has('loginId')){
            return redirect('login')->with('fail','You have to login first');
        }
        return $next($request);
    }
}
