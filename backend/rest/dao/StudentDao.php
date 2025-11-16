<?php
require_once __DIR__ . '/BaseDao.php';

class StudentDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "students";
        parent::__construct($this->table_name);
    }

    public function getByEmail($email)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE email=:email', ['email' => $email]);
    }

    public function getByDepartment($department_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE department_id=:department_id', ['department_id' => $department_id]);
    }

    public function countStudentsPaginated($search)
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

    public function getStudentsPaginated($offset, $limit, $search, $order_column, $order_direction)
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