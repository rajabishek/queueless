<?php 

namespace Queueless\Repositories\Eloquent;

use Queueless\Organisation;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Exceptions\OrganisationNotFoundException;

class OrganisationRepository extends AbstractRepository implements OrganisationRepositoryInterface
{
    /**
     * Create a new DbOrganisationRepository instance.
     *
     * @param  \Queueless\Organisation  $organisation
     * @return void
     */
    public function __construct(Organisation $organisation)
    {
        $this->model = $organisation;
    }

    /**
     * Get the organisation curresponding to the domain
     *
     * @param  string $domain
     * @return \Queueless\Organisation
     */
    public function findByDomain($domain)
    {
        $organisation = $this->model->where('domain',$domain)->first();

        if(is_null($organisation))
            throw new OrganisationNotFoundException("There is no organisation with domain as $domain");

        return $organisation;
    }

    /**
     * Find the organisation by the given the given confirmation code.
     *
     * @param  int  $id
     * @return \Queueless\User
     */
    public function findByConfirmationCode($confirmationCode)
    {
        $organisation = $this->model->where('confirmation_code',$confirmationCode)->first();

        if(is_null($organisation))
            throw new OrganisationNotFoundException("The organisation having confirmation code as $confirmationCode does not exist.");

        return $organisation;
    }

    /**
     * Does the organisation have users in the queue.
     *
     * @param  \Queueless\Organisation $organisation
     * @return boolean
     */
    public function doesHaveUsersInQueue(Organisation $organisation)
    {
        return $organisation->users()->count() > 0;
    }

    /**
     * Create a new organisation in the database.
     *
     * @param  array $data
     * @return \Queueless\Organisation
     */
    public function create(array $data)
    {
        $organisation = $this->getNew();

        $organisation->name = $data['name'];
        $organisation->domain = $data['domain'];

        if(isset($data['confirmation_code']) && $data['confirmation_code'])
            $organisation->confirmation_code  = $data['confirmation_code'];

        $organisation->save();

        return $organisation;
    }
}
