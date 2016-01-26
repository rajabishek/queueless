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
     * Find all users paginated.
     *
     * @param  Queueless\Organisation $organisation
     * @param  int  $perPage
     * @return Illuminate\Database\Eloquent\Collection|\Queueless\User[]
     */
    public function findAllPaginatedForOrganisation($organisation, $perPage = 8)
    {
        return $organisation->users()
                    ->where('designation','!=','Admin')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
    }

   /**
     * Find the user by the given id belonging to the given organisation.
     *
     * @param  int  $id
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\User
     */
    public function findByIdForOrganisation($id, Organisation $organisation)
    {
        $user = $organisation->users()->find($id);

        if(is_null($user))
            throw new UserNotFoundException('The user with id as "'.$id.'" does not exist!');

        return $user;
    }

    /**
     * Find the user by the given email address from the given organisation.
     *
     * @param  int  $email
     * @param  \Queueless\Organisation  $organisation
     * @return \Queueless\User
     */
    public function findByEmailForOrganisation($email, Organisation $organisation)
    {
        $user = $organisation->users()->where('email',$email)->first();

        if(is_null($user))
            throw new UserNotFoundException("The user having email as $email does not exist, for {$organisation->name}");

        return $user;
    }

    /**
     * Create a new user in the database.
     *
     * @param  array $data
     * @return \Queueless\User
     */
    public function createForOrganisation(array $data, Organisation $organisation)
    {
        $user = $this->getNew();

        $user->email        = $data['email'];
        $user->fullname     = $data['fullname'];
        $user->password     = $this->hasher->make($data['password']);
        
        if(isset($data['mobile']) && $data['mobile'])
            $user->mobile  = $data['mobile'];

        if(isset($data['address']) && $data['address'])
            $user->address  = $data['address'];
        
        $organisation->users()->save($user);

        return $user;
    }

    /**
     * Update the user in the database.
     *
     * @param  \Queueless\User $user
     * @param  array $data
     * @return \Queueless\User
     */
    public function edit(User $user, array $data)
    {

        //In setting page the user is not allowed to change his email or designation
        if(isset($data['email']))
            $user->email  = $data['email'];
        
        if(isset($data['designation']))
            $user->designation  = $data['designation'];
        
        $user->fullname     = $data['fullname'];
        $user->mobile  = $data['mobile'];
        $user->address  = $data['address'];

        //Sometimes the admin can update other details apart from the password
        //Update the password only if the admin does it.
        if(isset($data['password']))
            $user->password = $this->hasher->make($data['password']);

        $user->save();

        return $user;
    }
}
