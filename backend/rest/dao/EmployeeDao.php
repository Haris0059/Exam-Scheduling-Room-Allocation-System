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

    public function getByFirstName($first_name)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE first_name=:first_name', ['first_name' => $first_name]);
        
    }

    public function getByLastName($last_name)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE last_name=:last_name', ['last_name' => $last_name]);
    }

    public function getByEmail($email)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE email=:email', ['email' => $email]);
    }

    public function getByRole($role)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE role=:role', ['role' => $role]);
    }

    public function getByStatus($status)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE status=:status', ['status' => $status]);
    }

    public function getByDepartment($department_id)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE department_id=:department_id', ['department_id' => $department_id]);
    }

    public function getByFaculty($faculty_id)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE faculty_id=:faculty_id', ['faculty_id' => $faculty_id]);
    }

    public function countEmployeesPaginated($search)
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

    public function getEmployeesPaginated($offset, $limit, $search, $order_column, $order_direction)
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
?>