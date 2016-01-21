<?php 

namespace Queueless\Repositories;

use Queueless\User;

interface UserRepositoryInterface
{
    /**
     * Create a new user in the database.
     *
     * @param  array $data
     * @return \Queueless\User
     */
    public function create(array $data);

    /**
     * Update the user in the database.
     *
     * @param  \Queueless\User $user
     * @param  array $data
     * @return \Queueless\User
     */
    public function edit(User $user, array $data);

    /**
     * Find all users paginated.
     *
     * @param  int  $perPage
     * @return Illuminate\Database\Eloquent\Collection|\Queueless\User[]
     */
    public function findAllPaginated($perPage = 8);

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
}