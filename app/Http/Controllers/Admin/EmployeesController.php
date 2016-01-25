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
     * @param  \Queueless\Repositories\EmployeeRepositoryInterface $employees
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
            $employees = $this->employees->searchByTermPaginatedForOrganisation($search,$request->user()->organisation);
            $term = $search;
        }
        else
        {
            $employees = $this->employees->findAllPaginatedForOrganisation($request->user()->organisation);
            $message = "You haven't added any employees, we suggest you to add one.";
            $term = 'All';
        }

        return view('admin.employees.index', compact('domain','employees','designationList','term','message'));
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
        
        return view('admin.employees.create',compact('domain','genderList'));
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

        return view('admin.employees.download',compact('domain','orderByList','orderTypeList'));
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
        
        return redirect()->route('admin.employees.getDownload',$domain);
    }

    /**
     * Present a form to edit an existing new user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $id, Request $request)
    {    
        $employee = $this->employees->findByIdForOrganisation(
            $id,
            $request->user()->organisation
        );
        return view('admin.employees.edit',compact('domain','employee'));
    }

    /**
     * Display a single user's details
     *
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id, Request $request)
    {
        try
        {
            $employee = $this->employees->findByIdForOrganisation($id,$request->user()->organisation);
            
            if($employee->hasRole('Admin'))
                return redirect()->route('admin.employees.index');
            
            return view('admin.employees.show',compact('domain','employee'));
        }
        catch(EmployeeNotFoundException $e)
        {
            $backLink = route('admin.employees.index',$domain);
            return view('errors.employeenotfound',compact('domain','backLink'));
        }
    }


    /**
     * Handle the creation of a new user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($domain, Request $request)
    {
        $rules = [
            'email'  => 'required|email|unique:employees',
            'fullname'  => 'required',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required'
        ];

        if($request->get('mobile'))
            $rules['mobile'] = 'required|numeric|digits:10|unique:employees';

        $this->validate($request, $rules);
        $employee = $this->employees->createForOrganisation(
            $request->all(),
            $request->user()->organisation
        );
        
        flash()->success("$employee->fullname has been added as an employee in your organisation.");
        return redirect()->route('admin.employees.show',[$domain,$employee->id]);
    }

    /**
     * Update the user with the new data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($domain, $id, Request $request)
    {
        $rules = [
            'email'  => 'required|email',
            'fullname'  => 'required'
        ];

        $rules['email'] = "required|email|unique:employees,email,{$id}";
        $rules['mobile'] = "unique:employees,mobile,{$id}";
        
        if($request->get('password'))
            $rules['password'] = 'required|confirmed';

        if($request->get('designation'))
            $rules['designation'] = 'required|in:Employee';

        $this->validate($request, $rules);

        $employee = $this->employees->findByIdForOrganisation(
            $id,
            $request->user()->organisation
        );
        $employee = $this->employees->edit($employee,$request->all());

        flash()->success("{$employee->fullname}'s details has been successfully updated.");
        return redirect()->route('admin.employees.show',[$domain,$id]);
    }

    /**
     * Handle the process of destroying an existing user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($domain, $id){
        
        $employee = $this->employees->findByIdForOrganisation($id,$request->user()->organisation);
        $employee->delete();

        flash()->success("$employee->fullname has been successfully removed as a $employee->designation from your organisation.");
        return redirect()->route('admin.employees.index',$domain);
    }
}
