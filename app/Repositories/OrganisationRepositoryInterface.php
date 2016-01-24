<?php 

namespace Queueless\Repositories;

use Queueless\User;
use Queueless\Organisation;
use Queueless\Services\Forms\LoginForm;
use Queueless\Services\Forms\AddEmployeeForm;
use Queueless\Services\Forms\EmployeeUpdateForm;
use Queueless\Services\Forms\SettingsForm;
use Queueless\Services\Forms\ChangePasswordForm;

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
     * Create a new organisation in the database.
     *
     * @param  array $data
     * @return \Queueless\Organisation
     */
    public function create(array $data);
}