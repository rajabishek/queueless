<?php

namespace Queueless\Http\Controllers\Admin;

use Queueless\Http\Controllers\Controller;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Services\Settings\ManagesSettingsAndHandlesUserProfile;


class SettingsController extends Controller {

    /**
     * User repository.
     *
     * @var \Queueless\Repositories\EmployeeRepositoryInterface
     */
    protected $employees;

    /**
     * Create a new PublisherSettingsController instance.
     *
     * @param  \Queueless\Repositories\EmployeeRepositoryInterface  $employees 
     * @return void
     */
    public function __construct(EmployeeRepositoryInterface $employees)
    {
        $this->employees = $employees;
    }

    /**
     * Trait to handle settings and profile upload.
     *
     * @see \Queueless\Services\Settings\ManagesSettingsAndHandlesUserProfile
     */
    use ManagesSettingsAndHandlesUserProfile;

    /**
     * The name of the view to render for the settings page
     *
     * @var string
     */
    protected $settingsView = 'admin.settings';

    /**
     * The route name for the settings page
     *
     * @var string
     */
    protected $settingsRoute = 'admin.settings.index';
}