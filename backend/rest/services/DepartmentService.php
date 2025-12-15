<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/DepartmentDao.php';

class DepartmentService extends BaseService {

    protected $departmentDao; 

    public function __construct()
    {
        $dao = new DepartmentDao();

        parent::__construct($dao);
    }

    public function getByFaculty($faculty_id) {
        if (!is_numeric($faculty_id)) {
            throw new Exception("Invalid faculty ID");
        }
        return $this->dao->getByFaculty($faculty_id);
    }
}
?>