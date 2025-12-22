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

    public function getCourseStudents($course_id)
    {
        return $this->dao->getCourseStudents($course_id);
    }

     public function getStudentsByCourse($course_id)
    {
        $request = Flight::request()->query;

        $draw   = (int)($request['draw'] ?? 0);
        $start  = (int)($request['start'] ?? 0);
        $length = (int)($request['length'] ?? 10);

        $search = $request['search']['value'] ?? '';

        $orderColumnIndex = $request['order'][0]['column'] ?? 0;
        $orderDirection   = $request['order'][0]['dir'] ?? 'asc';

        // must match DataTables column order
        $columns = [
            'student_id',
            'full_name',
            'academic_level',
            'department'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'full_name';

        $data = $this->dao->getEnrollmentsPaginated(
            $start,
            $length,
            $search,
            $orderColumn,
            $orderDirection
        );

        $count = $this->dao
            ->countEnrollmentsPaginated($search)['count'];

        Flight::json([
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => $data
        ]);
    }
}
?>