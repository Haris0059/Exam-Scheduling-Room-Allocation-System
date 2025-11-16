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


    public function getEmployeesPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->dao->countEmployeesPaginated($search)['count'];
        $data = $this->dao->getEmployeesPaginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'total_records' => $count,
            'data' => $data
        ];
    }

    public function getEmployeeByFirstName($first_name)
    {
        return $this->dao->getByFirstName($first_name);
    }

    public function getEmployeeByLastName($last_name)
    {
        return $this->dao->getByLastName($last_name);
    }

    public function getEmployeeByEmail($email)
    {
        return $this->dao->getByEmail($email);
    }

    public function getEmployeesByRole($role)
    {
        return $this->dao->getByRole($role);
    }

    public function getEmployeesByStatus($status)
    {
        return $this->dao->getByStatus($status);
    }

    public function getEmployeesByDepartment($department_id)
    {
        return $this->dao->getByDepartment($department_id);
    }

    public function getEmployeesByFaculty($faculty_id)
    {
        return $this->dao->getByFaculty($faculty_id);
    }
}