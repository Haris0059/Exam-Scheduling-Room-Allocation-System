<?php
require_once __DIR__ . '/BaseDao.php';

class EmployeeDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "employees";
        parent::__construct($this->table_name);
    }

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE id=:id', ['id' => $id]);
    }

    public function get_by_first_name($first_name)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE first_name=:first_name', ['first_name' => $first_name]);
        
    }

    public function get_by_last_name($last_name)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE last_name=:last_name', ['last_name' => $last_name]);
    }

    public function get_by_email($email)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE email=:email', ['email' => $email]);
    }

    public function get_by_role($role)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE role=:role', ['role' => $role]);
    }

    public function get_by_status($status)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE status=:status', ['status' => $status]);
    }

    public function get_by_department($department_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE department_id=:department_id', ['department_id' => $department_id]);
    }

    public function get_by_faculty($faculty_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE faculty_id=:faculty_id', ['faculty_id' => $faculty_id]);
    }

    public function count_employees_paginated($search)
    {
        $query = "SELECT COUNT(*) AS count
                  FROM " . $this->table_name . "
                  WHERE LOWER(first_name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(last_name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(email) LIKE CONCAT('%', :search, '%');";
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_employees_paginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE LOWER(first_name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(last_name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(email) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        return $this->query($query, [
            'search' => $search
        ]);
    }
}