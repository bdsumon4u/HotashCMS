<?php

namespace App\Http\Middleware;

use Hotash\Authable\Middleware\RedirectIfAuthenticated as Middleware;

class RedirectIfAuthenticated extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $as
     * @return string|null
     */
    protected function redirectTo($request, $as)
    {
        return route($as.'dashboard');
    }
}
