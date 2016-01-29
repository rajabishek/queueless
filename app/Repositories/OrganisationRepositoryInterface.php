<?php 

namespace Queueless\Repositories;

use Queueless\Organisation;

interface OrganisationRepositoryInterface
{
    /**
     * Get the organisation curresponding to the domain
     *
     * @param  string $domain
     * @return \Queueless\Organisation
     */
    public function findByDomain($domain);

    /**
     * Find the organisation by the given the given confirmation code.
     *
     * @param  int  $id
     * @return \Queueless\User
     */
    public function findByConfirmationCode($confirmationCode);

    /**
     * Does the organisation have users in the queue.
     *
     * @param  \Queueless\Organisation $organisation
     * @return boolean
     */
    public function doesHaveUsersInQueue(Organisation $organisation);

    /**
     * Create a new organisation in the database.
     *
     * @param  array $data
     * @return \Queueless\Organisation
     */
    public function create(array $data);
}