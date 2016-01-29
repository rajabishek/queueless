<?php 

namespace Queueless\Repositories;

use Queueless\User;
use Queueless\Organisation;

interface UserRepositoryInterface
{
    /**
     * Find the user by the given id.
     *
     * @param  int  $id
     * @return \Queueless\User
     */
    public function findById($id);

    /**
     * Find the user by the given email address.
     *
     * @param  int  $email
     * @return \Queueless\User
     */
    public function findByEmail($email);

    /**
     * Add the given user to the organisation's queue.
     *
     * @param  \Queueless\User  $user
     * @param  \Queueless\Organisation $organisation
     * @return boolean
     */
    public function addUserToQueueInOrganisation(User $user, Organisation $organisation);

    /**
     * Get the first user from queue in the given organisation.
     *
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\User
     */
    public function getUserFromQueueInOrganisation(Organisation $organisation);
}