<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Closure;

class AdministratorAccess
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
        if(Gate::denies('administrator-access'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        return $next($request);
    }
}
