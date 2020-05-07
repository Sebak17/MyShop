<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class LogoutUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            if($user->ban != null) {
                Auth::logout();
                return redirect()->route('loginPage');
            }

        }

        return $next($request);
    }
}
