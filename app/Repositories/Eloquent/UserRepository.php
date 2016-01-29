<?php

namespace Queueless\Repositories\Eloquent;

use Queueless\User;
use Queueless\Organisation;
use Queueless\Repositories\UserRepositoryInterface;
use Queueless\Exceptions\UserNotFoundException;
use Illuminate\Contracts\Hashing\Hasher;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * User model.
     *
     * @var \Queueless\User
     */
    protected $model;

    /**
     * Bcrypt hasher to hash the password.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Create a new DbUserRepository instance.
     *
     * @param  \Queueless\User  $user
     * @return void
     */
    public function __construct(User $user, Hasher $hasher)
    {
        $this->model = $user;
        $this->hasher = $hasher;
    }

   /**
     * Find the user by the given id.
     *
     * @param  int  $id
     * @return \Queueless\User
     */
    public function findById($id)
    {
        $user = $this->model->find($id);

        if(is_null($user))
            throw new UserNotFoundException('The user with id as "'.$id.'" does not exist!');

        return $user;
    }

    /**
     * Find the user by the given email address.
     *
     * @param  int  $email
     * @return \Queueless\User
     */
    public function findByEmail($email)
    {
        $user = $this->model->where('email',$email)->first();

        if(is_null($user))
            throw new UserNotFoundException("The user having email as $email does not exist.");

        return $user;
    }

    /**
     * Add the given user to the organisation's queue.
     *
     * @param  \Queueless\User  $user
     * @param  \Queueless\Organisation $organisation
     * @return boolean
     */
    public function addUserToQueueInOrganisation(User $user, Organisation $organisation)
    {
        $data = $organisation->users()->lists('users.id')->toArray();
        if($data)
            array_push($data, $user->id);
        else
            $data = [$user->id];
        return $organisation->users()->sync($data);
    }

    /**
     * Get the first user from queue in the given organisation.
     *
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\User
     */
    public function getUserFromQueueInOrganisation(Organisation $organisation)
    {
        $user = $organisation->users()->first();

        if(is_null($user))
            throw new UserNotFoundException("There are no users in the queue.");

        $organisation->users()->detach($user->id);
        return $user;
    }
}
