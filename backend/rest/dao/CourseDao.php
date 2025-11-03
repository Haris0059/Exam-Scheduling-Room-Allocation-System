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

    public function get_by_code($code)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE code %LIKE% :code', ['code' => $code]);
    }

    public function get_by_name($name)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE name %LIKE% :name', ['name' => $name]);
    }

    public function get_by_academic_level($academic_level)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE academic_level=:academic_level', ['academic_level' => $academic_level]);
    }

    public function get_by_faculty($faculty_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE faculty_id=:faculty_id', ['faculty_id' => $faculty_id]);
    }
    
    public function get_by_department($department_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE department_id=:department_id', ['department_id' => $department_id]);
    }

    public function count_courses_paginated($search)
    {
        $query = "SELECT COUNT(*) AS count
                  FROM " . $this->table_name . "
                  WHERE LOWER(name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(code) LIKE CONCAT('%', :search, '%')";
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_courses_paginated($offset, $limit, $search, $order_column, $order_direction)
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