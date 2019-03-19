<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {        
        switch ($guard) {
            case 'admin':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('dashboard');
                }
            break;
            case 'web':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('quiz.login', ['slug' => $request->route('slug')]);
                }
            break;
            default:
                if (Auth::guard($guard)->check()) {
                    return redirect()->to("/");
                }
            break;
        }
        return $next($request);
    }
}
