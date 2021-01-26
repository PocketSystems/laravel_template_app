<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->route()->getName() !== "login" && Auth::check() && Auth::user()->type !== "ADMIN" && (Auth::user()->company->status == 0 or Auth::user()->status == 0)){
            Auth::logout();
            return redirect('login');
        }
        return parent::handle($request, $next, $guards); // TODO: Change the autogenerated stub
    }
}
