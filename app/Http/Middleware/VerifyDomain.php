<?php

namespace Queueless\Http\Middleware;

use Closure;
use Queueless\Services\Response\CanRespondInJson;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Exceptions\OrganisationNotFoundException;

class VerifyDomain
{
    /**
     * Trait to handle responding in JSON with proper status codes.
     *
     * @see \Queueless\Services\Response\CanRespondInJson
     */
    use CanRespondInJson;

    /**
     * Organisation repository.
     *
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $organisations;

    /**
     * Create a new NotificationsListener instance.
     *
     * @param  \Queueless\Repositories\OrganisationRepositoryInterface $organisations
     * @return void
     */
    public function __construct(OrganisationRepositoryInterface $organisations)
    {
        $this->organisations = $organisations;
    }

    /**
     * Check whether an incoming it is an incoming API request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    protected function isAnApiRequest($request)
    {
        return strpos($request->path(), 'api/v1') !== FALSE;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try
        {
            $domain = $request->route()->getParameter('domain');
            $organisation = $this->organisations->findByDomain($domain);

            if(!$organisation->confirmed)
            {
                if($this->isAnApiRequest($request))
                    return $this->respondWithError("The admin has not verified their email address.");

                return redirect()->route('auth.verification.getResend');
            }
        }
        catch(OrganisationNotFoundException $e)
        {
            if($this->isAnApiRequest($request))
            {
                return $this->respondBadRequest("There is no company registered in name of {$domain}.");
            }
            else if($request->ajax())
            {
                return $this->respondBadRequest("There is no company registered in name of {$domain}.");
            }
            else
            {
                return redirect()->route('pages.home');
            }
        }
        catch(Exception $e)
        {
            return $this->respondInternalError();
        }

        return $next($request);
    }
}
