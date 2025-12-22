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

    public function getCourseStudents($course_id)
    {
        $query = "
            SELECT ce.student_id,
                   CONCAT(s.first_name, ' ', s.last_name) AS full_name,
                   s.academic_level,
                   d.name AS department
            FROM course_enrollments ce
            JOIN students s ON ce.student_id = s.id
            LEFT JOIN departments d ON s.department_id = d.id
            WHERE ce.course_id = :course_id
              AND ce.status = 'active'
        ";
    
        return $this->query($query, ["course_id" => $course_id]);
    }

    public function getEnrollmentsPaginated($offset, $limit, $search, $order_column, $order_direction) 
    {
        $query = "SELECT ce.student_id,
                         CONCAT(s.first_name, ' ', s.last_name) AS full_name,
                         s.academic_level,
                         d.name AS department
                  FROM " . $this->table_name . " ce
                  JOIN students s ON ce.student_id = s.id
                  LEFT JOIN departments d ON s.department_id = d.id
                  WHERE ce.status = 'active'
                    AND (
                        LOWER(s.first_name) LIKE CONCAT('%', :search, '%')
                        OR LOWER(s.last_name) LIKE CONCAT('%', :search, '%')
                    )
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";

        return $this->query($query, [
            'search' => $search
        ]);
    }

    public function countEnrollmentsPaginated($search)
{
    $query = "
        SELECT COUNT(*) AS count
        FROM {$this->table_name} ce
        JOIN students s ON ce.student_id = s.id
        WHERE ce.status = 'active'
          AND (
              LOWER(s.first_name) LIKE CONCAT('%', :search, '%')
              OR LOWER(s.last_name) LIKE CONCAT('%', :search, '%')
          )
    ";

    return $this->query_unique($query, [
        'search' => $search
    ]);
}


}
?>