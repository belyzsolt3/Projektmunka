<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
//use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Session()->has('loginId')){
            //admin role == 1
            //user role == 0
            $user = User::find(session('loginId'));
            if ($user->role == '1'){
                return $next($request);
            }else{
                return redirect('/dashboard')->with('fail', 'Access Denied as you are not Admin!')->with('expires', time() + 10);
            }
        }else{
            return redirect('/login')->with('fail', 'Login to access the website info!');
        }
        return $next($request);
    }
}
