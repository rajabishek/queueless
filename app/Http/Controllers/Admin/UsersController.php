<?php

namespace Queueless\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Queueless\Http\Requests;
use Queueless\Http\Controllers\Controller;
use Queueless\Repositories\UserRepositoryInterface;

class UsersController extends Controller
{
	/**
     * User repository.
     *
     * @var \Queueless\Repositories\UserRepositoryInterface
     */
    protected $users;

    /**
     * Create a new UsersController instance.
     *
     * @param  \Queueless\Repositories\UserRepositoryInterface $users
     * @return void
     */
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * Show the users index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {
    	return 'cool';
    }

    /**
     * Get the users waiting in the queue.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQueueJson($domain, Request $request)
    {
        $organisation = $request->user()->organisation;
        $users = $this->users->getUsersFromQueueForOrganisation($organisation)->toArray();

        $attendingUsers = $this->users->getUsersInProgressForOrganisation($organisation);
        return compact('users','attendingUsers');
    }

    /**
     * Get the users waiting in the queue.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQueue($domain, Request $request)
    {
        return view('admin.users.queue',compact('domain'));
    }

    /**
     * Present a form to create a new user
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain)
    {
        
    }

    /**
     * Present a form to edit an existing new user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $id, Request $request)
    {    

    }

    /**
     * Display a single user's details
     *
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id, Request $request)
    {

    }


    /**
     * Handle the creation of a new user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($domain, Request $request)
    {

    }

    /**
     * Update the user with the new data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($domain, $id, Request $request)
    {

    }

    /**
     * Handle the process of destroying an existing user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($domain, $id)
    {
        
    }
}
