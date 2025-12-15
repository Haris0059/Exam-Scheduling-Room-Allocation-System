<?php
class DepartmentDao extends BaseDao {

    protected $table_name;

    public function __construct()
    {
        $this->table_name = "departments";
        parent::__construct($this->table_name);
    }

    public function getByFaculty($faculty_id) 
    {
        return $this->query(
            "SELECT id, name FROM {$this->table_name} WHERE faculty_id = :faculty_id",
            ["faculty_id" => $faculty_id]
        );
    }
}
?>