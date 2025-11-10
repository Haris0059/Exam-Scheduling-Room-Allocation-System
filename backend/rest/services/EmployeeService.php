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

}