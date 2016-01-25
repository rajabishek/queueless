<?php 

namespace Queueless\Reporting;

use Queueless\Reporting\Contracts\FileReportGenerator;
use Queueless\User;
use Queueless\Organisation;
use Maatwebsite\Excel\Excel;

class ExcelReportGenerator implements FileReportGenerator{

    /**
     * The column name to use for ordering the employee list.
     *
     * @var string
     */
    protected $orderby = 'fullname';

    /**
     * Whether to generate the report in ascending or descending order.
     *
     * @var string
     */
    protected $ordertype = 'asc';

    /**
     * Create an instance of ExcelReportGenerator
     *
     * @param  \Excel  $user
     * @return void
     */
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    /**
     * Set the column name to order the employee list
     *
     * @param  string  $orderby
     * @return \Queueless\Reporting\FileReportGenerator
     */
    public function orderby($orderby)
    {
        $this->orderby = $orderby;

        return $this;
    }

    /**
     * Set whether to order in ascending or descending.
     *
     * @param  string  $ordertype
     * @return \Queueless\Reporting\FileReportGenerator
     */
    public function ordertype($ordertype)
    {
        $this->ordertype = $ordertype;

        return $this;
    }

    /**
     * Get the employee details as a report in a file from the given organisation.
     *
     * @param  \Queueless\Organisation  $organisation
     * @return bool
     */
    public function getEmployeeDetailsForOrganisation(Organisation $organisation)
    {
        $orderby = $this->orderby;
        $ordertype = $this->ordertype;

        $data = $organisation->employees()->select('email','fullname','address','mobile','designation')
                              ->orderBy($orderby,$ordertype)
                              ->take(100)
                              ->get();

        $this->excel->create('Employee Details', function($excel) use($orderby,$ordertype,$organisation,$data){

            $excel->setTitle('Employee Details')
                  ->setCreator('Administrator')
                  ->setCompany($organisation->name)
                  ->setDescription("Contains the details of all employess in {$organisation->name}");

            //Create a new sheet inside our newly created file
            $excel->sheet('Employees', function($sheet) use($orderby,$ordertype,$data){
                $sheet->setOrientation('landscape')->fromModel($data);
            });

        })->export('xls');
    }
}
