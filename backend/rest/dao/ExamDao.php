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

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE id=:id', ['id' => $id]);
    }

    public function get_by_date($date)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE date=:date', ['date' => $date]);
    }

    public function get_by_department($department_id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE department_id=:department_id', ['department_id' => $department_id]);
    }

    public function get_by_type($type)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE type=:type', ['type' => $type]);
    }
}