<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ExamDao.php';

class ExamService extends BaseService
{
    protected $examDao;

    public function __construct()
    {
        $dao = new ExamDao();
        parent::__construct($dao);
    }

    // Add your service methods here

    
}
