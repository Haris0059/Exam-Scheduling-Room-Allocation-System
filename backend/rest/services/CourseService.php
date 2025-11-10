<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CourseDao.php';

class CourseService extends BaseService
{
    protected $courseDao;

    public function __construct()
    {
        $dao = new CourseDao();

        parent::__construct($dao);
    }

    // Add your service methods here
}
