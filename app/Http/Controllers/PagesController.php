<?php

namespace Queueless\Http\Controllers;

use Illuminate\Http\Request;
use Queueless\Http\Requests;
use Queueless\Http\Controllers\Controller;

class PagesController extends Controller
{
    /**
     * Create a new PagesController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the home page of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHome()
    {
    	return view('pages.home');
    }
}
