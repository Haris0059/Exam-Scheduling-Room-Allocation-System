<?php
require_once __DIR__ . '/BaseDao.php';

class Facultydao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "faculty";
        parent::__construct($this->table_name);
    }
}
?>