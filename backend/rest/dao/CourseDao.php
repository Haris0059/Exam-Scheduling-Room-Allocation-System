<?php
require_once __DIR__ . '/BaseDao.php';

class CourseDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "courses";
        parent::__construct($this->table_name);
    }

    public function getByCode($code)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE code %LIKE% :code', ['code' => $code]);
    }

    public function getByName($name)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE name %LIKE% :name', ['name' => $name]);
    }

    public function getByAcademicLevel($academic_level)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE academic_level=:academic_level', ['academic_level' => $academic_level]);
    }

    public function getByFaculty($faculty_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE faculty_id=:faculty_id', ['faculty_id' => $faculty_id]);
    }
    
    public function getByDepartment($department_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE department_id=:department_id', ['department_id' => $department_id]);
    }

    public function countCoursesPaginated($search)
    {
        $query = "SELECT COUNT(*) AS count
                  FROM " . $this->table_name . "
                  WHERE LOWER(name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(code) LIKE CONCAT('%', :search, '%')";
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function getCoursesPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE LOWER(name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(code) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        return $this->query($query, [
            'search' => $search
        ]);
    }
}