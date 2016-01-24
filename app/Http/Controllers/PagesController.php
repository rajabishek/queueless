<?php

namespace Queueless\Http\Controllers;

use Illuminate\Http\Request;
use Queueless\Http\Requests;
use Queueless\Http\Controllers\Controller;

class PagesController extends Controller
{
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
