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

    public function getCoursesPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->dao->countCoursesPaginated($search)['count'];

        $data = $this->dao->getCoursesPaginated($offset, $limit, $search, $order_column, $order_direction);

        // Return both in a structured array
        return [
            'total_records' => $count,
            'data' => $data
        ];
    }

    public function getCourseByCode($code)
    {
        return $this->dao->getByCode($code);
    }

    public function getCourseByName($name)
    {
        return $this->dao->getByName($name);
    }

    public function getCoursesByFaculty($faculty_id)
    {
        return $this->dao->getByFaculty($faculty_id);
    }

    public function getCoursesByDepartment($department_id)
    {
        return $this->dao->getByDepartment($department_id);
    }
}