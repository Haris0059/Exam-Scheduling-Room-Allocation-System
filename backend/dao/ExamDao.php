<?php
require_once __DIR__ . '/BaseDao.php';

class ExamDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "exams";
        parent::__construct($this->table_name);
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM ' . $this->table_name, []);
    }

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE id=:id', ['id' => $id]);
    }

    public function get_by_code($code)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE code=:code', ['code' => $code]);
    }

    public function get_by_department($type)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE type=:type', ['type' => $type]);
    }

    public function count_exams_paginated($search)
    {
        $query = "SELECT COUNT(*) AS count
                  FROM " . $this->table_name . "
                  WHERE LOWER(name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(email) LIKE CONCAT('%', :search, '%');";
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_exams_paginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE LOWER(name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(email) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        return $this->query($query, [
            'search' => $search
        ]);
    }
}