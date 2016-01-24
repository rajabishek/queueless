<?php

namespace Queueless\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class Designation
{
    /**
     * The authentication guard instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    
    /**
     * Create a new admin middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
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
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $domain = $request->route()->getParameter('domain');
        
        if($this->auth->guest())
            return redirect()->route('auth.getLogin',$domain);

        if($this->auth->check() && $this->auth->user()->organisation->domain != $domain)
        {
            $this->auth->logout($this->auth->user());
            flash()->error("You don't have permissions to use the application.");
            return redirect()->route('auth.getLogin',$domain);
        }

        if($this->auth->check() && ! $this->auth->user()->hasRole($role) )
            return view('errors.404');

        return $next($request);
    }
}