<?php
require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao {
   protected $table_name;

   public function __construct() {
       $this->table_name = "employees";
       parent::__construct($this->table_name);
   }

    public function get_user_by_email($email) {
        $sql = "SELECT * FROM employees WHERE email = :email";
        return $this->query_unique($sql, ['email' => $email]);
    }

    public function get_user_by_id($id) {
        $sql = "SELECT * FROM employees WHERE id = :id";
        return $this->query_unique($sql, ['id' => $id]);
    }
}
