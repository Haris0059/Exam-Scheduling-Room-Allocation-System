<?php
require_once __DIR__ . '/BaseDao.php';

class CourseEnrollmentDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "course_enrollments"; 
        parent::__construct($this->table_name);
    }

    public function getCountByCourse($course_id)
    {
        $query = "SELECT COUNT(*) AS total
                  FROM " . $this->table_name . "
                  WHERE course_id = :course_id 
                  AND status = 'active'";
                  
        $result = $this->query_unique($query, ['course_id' => $course_id]);
        
        // IT HAS TO RETURN A INTEGER!!!!!!!!!!!!!!!!!!!!!!!!
        return (int)$result['total'];
    }
}
?>