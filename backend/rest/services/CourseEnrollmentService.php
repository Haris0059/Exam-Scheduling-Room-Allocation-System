<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CourseEnrollmentDao.php';

class CourseEnrollmentService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new CourseEnrollmentDao());
    }

    public function getCountByCourse($course_id)
    {
        return $this->dao->getCountByCourse($course_id);
    }
}