<?php 

namespace Queueless\Repositories;

use Queueless\User;
use Queueless\Organisation;

interface UserRepositoryInterface
{
    /**
     * Find all users paginated.
     *
     * @param  Queueless\Organisation $organisation
     * @param  int  $perPage
     * @return Illuminate\Database\Eloquent\Collection|\Queueless\User[]
     */
    public function findAllPaginatedForOrganisation($organisation, $perPage = 8);

   /**
     * Find the user by the given id belonging to the given organisation.
     *
     * @param  int  $id
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\User
     */
    public function findByIdForOrganisation($id, Organisation $organisation);

    /**
     * Find the user by the given email address from the given organisation.
     *
     * @param  int  $email
     * @param  \Queueless\Organisation  $organisation
     * @return \Queueless\User
     */
    public function findByEmailForOrganisation($email, Organisation $organisation);

    /**
     * Create a new user in the database.
     *
     * @param  array $data
     * @return \Queueless\User
     */
    public function createForOrganisation(array $data, Organisation $organisation);

    /**
     * Update the user in the database.
     *
     * @param  \Queueless\User $user
     * @param  array $data
     * @return \Queueless\User
     */
    public function edit(User $user, array $data);
}