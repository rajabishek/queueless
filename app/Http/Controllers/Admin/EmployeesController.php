<?php

namespace Queueless\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Queueless\Http\Requests;
use Queueless\Http\Controllers\Controller;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Services\Upload\ExcelUploadService;
use Queueless\Exceptions\EmployeeNotFoundException;
use Queueless\Reporting\FileReportGeneratorManager;

class EmployeesController extends Controller
{
	/**
     * User repository.
     *
     * @var \Queueless\Repositories\EmployeeRepositoryInterface
     */
    protected $employees;

    /**
     * Create a new EmployeesController instance.
     *
     * @param  \Queueless\Repositories\EmployeeRepositoryInterface $users
     * @return void
     */
    public function __construct(EmployeeRepositoryInterface $employees)
    {
        $this->employees = $employees;
    }

    /**
     * Show the employees index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {
        $designationList['All'] = 'All';
        $designationList += $this->getDesignationList();

        $search = $request->get('q');
        if($search)
        {
            $message = "Coudn't find any employees matching the term <strong>$search</strong> for you. We suggest you to go back and search for another term once more.";
            $users = $this->employees->searchByTermPaginatedForOrganisation($search,$request->user()->organisation);
            $term = $search;
        }
        else
        {
            $users = $this->employees->findAllPaginatedForOrganisation($request->user()->organisation);
            $message = "You haven't added any employees, we suggest you to add one.";
            $term = 'All';
        }

        return view('admin.users.index', compact('domain','users','designationList','term','message'));
    }

    /**
     * Get the list of designations that the admin can add.
     *
     * @return array
     */
    protected function getDesignationList()
    {
        return [
            'Telecaller' => 'Telecaller',
            'Field Coordinator' => 'Field Coordinator',
            'Field Executive' => 'Field Executive',
            'Manager' => 'Manager',
            'Team Leader' => 'Team Leader'
        ];
    }

    /**
     * Present a form to create a new user
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain)
    {
        $genderList = ['Male' => 'Male','Female' => 'Female'];
        
        return view('admin.users.create',compact('domain','genderList'));
    }

    /**
     * Present a form for downloading user related reports
     *
     * @return \Illuminate\Http\Response
     */
    public function getDownload($domain){
        
        $orderByList = [
            'created_at' => 'Date of Joining',
            'fullname' => 'Full Name'
        ];

        $orderTypeList = [
            'asc' => 'Ascending',
            'desc' => 'Descending',
        ];

        return view('admin.users.download',compact('domain','orderByList','orderTypeList'));
    }

    /**
     * Present a form for downloading user related reports
     *
     * @return \Illuminate\Http\Response
     */
    public function postDownload($domain, Request $request, FileReportGeneratorManager $reporter)
    {
        extract($request->only('orderby','ordertype','format'));

        $reporter->format($format)
                 ->orderby($orderby)
                 ->ordertype($ordertype)
                 ->getEmployeeDetailsForOrganisation($request->user()->organisation);
        
        return redirect()->route('admin.users.getDownload',$domain);
    }

    /**
     * Present a form for importing employee data into application
     *
     * @return \Illuminate\Http\Response
     */
    public function getImport($domain)
    {
        return view('admin.users.import',compact('domain'));
    }

    /**
     * Upload the users data to the database from the uploaded file
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Queueless\Services\Upload\ExcelUploadService $uploader
     * @return \Illuminate\Http\Response
     */
    public function postImport($domain, Request $request, ExcelUploadService $uploader)
    {
        $organisation = $request->user()->organisation;
        $uploader = $uploader->forOrganisation($organisation);
        $users = $uploader->handle($request->file('file'));

        if($users)
        {
            foreach ($users as $user) {
                $user['password'] = 'password';
                $this->employees->createForOrganisation($user,$organisation);
            }
            return response()->json(['success' => true]);

        }
        else
        {
            return response()->json(['success' => false,'errors' => $uploader->getErrors()]);
        }
    }

    /**
     * Present a form to edit an existing new user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $id){
        
        $designationList = $this->getDesignationList();

        $genderList = ['Male' => 'Male','Female' => 'Female'];
        $user = $this->employees->findByIdForOrganisation($id,$request->user()->organisation);
        return view('admin.users.edit',compact('domain','user','designationList','genderList'));
    }

    /**
     * Display a single user's details
     *
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        try
        {
            $user = $this->employees->findByIdForOrganisation($id,$request->user()->organisation);
            
            if($user->hasRole('Admin'))
                return redirect()->route('admin.users.index');
            
            return view('admin.users.show',compact('domain','user'));
        }
        catch(EmployeeNotFoundException $e)
        {
            $backLink = route('admin.users.index',$domain);
            return view('errors.employeenotfound',compact('domain','backLink'));
        }
    }


    /**
     * Handle the creation of a new user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($domain, AddEmployeeForm $form, Request $request)
    {
        try
        {
            $input = $request->all();
            $form->validate($input);
            $user = $this->employees->createForOrganisation($input,$request->user()->organisation);
            
            flash()->success("$user->fullname has been added as a $user->designation in your organisation.");
            return redirect()->route('admin.users.show',[$domain,$user->id]);
        }
        catch(FormValidationException $e)
        {
            flash()->error('Please review the following errors.');
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Update the user with the new data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($domain, $id, EmployeeUpdateForm $form, Request $request)
    {
        $input = $request->all();
        $input['id'] = $id;

        try
        {
            $form->validate($input);
            $user = $this->employees->findByIdForOrganisation($id,$request->user()->organisation);
            $user = $this->employees->edit($user,$input);

            flash()->success("$user->fullname's details has been successfully updated.");
            return redirect()->route('admin.users.show',[$domain,$id]);
        }
        catch(FormValidationException $e)
        {
            flash()->error('Please review the following errors.');
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Handle the process of destroying an existing user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($domain, $id){
        
        $user = $this->employees->findByIdForOrganisation($id,$request->user()->organisation);
        $user->delete();

        flash()->success("$user->fullname has been successfully removed as a $user->designation from your organisation.");
        return redirect()->route('admin.users.index',$domain);
    }
}
