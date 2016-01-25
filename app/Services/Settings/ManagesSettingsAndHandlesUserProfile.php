<?php 

namespace Queueless\Services\Settings;

use Illuminate\Http\Request;
use Illuminate\Contracts\Hashing\Hasher;

trait ManagesSettingsAndHandlesUserProfile
{
	/**
	 * Display a listing of the resource.
	 * GET /teamleader/settings
	 *
	 * @return Response
	 */
	public function index($domain, Request $request)
	{
		$employee = $request->user();
		
        return view($this->settingsView,compact('domain','employee'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /teamleader/settings
	 *
	 * @return Response
	 */
	public function store($domain, Request $request)
	{
        $user = $request->user();

		$this->validate($request, [
            'fullname' => 'required',
            'mobile' => "numeric|digits:10|unique:employees,mobile,$user->id"
        ]);
        
        $this->employees->edit($user,$request->all());  

        flash()->success('You have succesfully saved the details.');
        return redirect()->route($this->settingsRoute,$domain);
	}

	/**
     * Save the changed password
     * POST /publisher/settings/changePassword
     *
     * @return Response
     */
    public function changePassword($domain, Hasher $hasher, Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|min:5', 
            'password' => 'required|min:5|confirmed',
        ]);
        
        $user = $request->user();

        // test input password against the existing one
        if($hasher->check($request->get('old_password'), $user->password))
        {
            $user->password = $hasher->make($request->get('password'));

            if($user->save())
            {
                flash()->success('The password has been successfully changed.');
                return redirect()->back();
            }  
        }

        flash()->error('Your old password is incorrect.');
        return redirect()->back();
    }
}