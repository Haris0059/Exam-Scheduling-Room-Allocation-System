<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/EmployeeDao.php';


class EmployeeService extends BaseService
{
    protected $employeeDao; 

    public function __construct()
    {
        $dao = new EmployeeDao();

        parent::__construct($dao);
    }

    private function sanitizeEmployee($employee) {
        if ($employee) {
            unset($employee['password']);
        }
        return $employee;
    }


    /**
     * OVERRIDE: We override getById to remove the password
     */
    public function getById($id) {
        $employee = parent::getById($id);
        
        // 2. Remove the password and return the clean data
        return $this->sanitizeEmployee($employee);
    }

    /**
     * OVERRIDE: We override getAll to remove the password from the list
     */
    public function getAll() {
        // 1. Get all employees using the parent method
        $employees = parent::getAll();

        // 2. Loop through and remove the password from each one
        foreach ($employees as $key => $employee) {
            $employees[$key] = $this->sanitizeEmployee($employee);
        }

        // 3. Return the clean list
        return $employees;
    }


 
    public function getEmployeesPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->dao->countEmployeesPaginated($search)['count'];
        $data = $this->dao->getEmployeesPaginated($offset, $limit, $search, $order_column, $order_direction);

        // 1. Loop through the data and remove passwords
        foreach ($data as $key => $employee) {
            $data[$key] = $this->sanitizeEmployee($employee);
        }

        return [
            'total_records' => $count,
            'data' => $data
        ];
    }

    public function getEmployeeByFirstName($first_name)
    {
        $employee = $this->dao->getByFirstName($first_name);
        return $this->sanitizeEmployee($employee);
    }

    public function getEmployeeByLastName($last_name)
    {
        $employee = $this->dao->getByLastName($last_name);
        return $this->sanitizeEmployee($employee);
    }

    public function getEmployeeByEmail($email)
    {
        $employee = $this->dao->getByEmail($email);
        return $this->sanitizeEmployee($employee);
    }

    public function getEmployeesByRole($role)
    {
        $employees = $this->dao->getByRole($role);
        foreach ($employees as $key => $employee) {
            $employees[$key] = $this->sanitizeEmployee($employee);
        }
        return $employees;
    }

    public function getEmployeesByStatus($status)
    {
        $employees = $this->dao->getByStatus($status);
        foreach ($employees as $key => $employee) {
            $employees[$key] = $this->sanitizeEmployee($employee);
        }
        return $employees;
    }

    public function getEmployeesByDepartment($department_id)
    {
        $employees = $this->dao->getByDepartment($department_id);
        foreach ($employees as $key => $employee) {
            $employees[$key] = $this->sanitizeEmployee($employee);
        }
        return $employees;
    }

    public function getEmployeesByFaculty($faculty_id)
    {
        $employees = $this->dao->getByFaculty($faculty_id);
        foreach ($employees as $key => $employee) {
            $employees[$key] = $this->sanitizeEmployee($employee);
        }
        return $employees;
    }
}
?>