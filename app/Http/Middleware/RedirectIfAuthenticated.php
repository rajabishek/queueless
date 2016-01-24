<?php

namespace Queueless\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class RedirectIfAuthenticated
{
    /**
     * Create a new RedirectIfAuthenticated filter instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

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
        $auth = $this->auth->guard($guard);
        
        if ($auth->check()) 
        {
            $domain = $auth->user()->organisation->domain;
            $home = $auth->user()->getHomeRoute();
            
            return redirect()->route($home,$domain);
        }

        return $next($request);
    }
}
