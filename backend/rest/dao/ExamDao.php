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

    public function getByDate($date)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE date=:date', ['date' => $date]);
    }

    public function getByType($type)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE type=:type', ['type' => $type]);
    }
}
?>