<?php
require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao {
   protected $table_name;

   public function __construct() {
       $this->table_name = "employees";
       parent::__construct($this->table_name);
   }

   public function get_user_by_email($email) {
       $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
       return $this->query_unique($query, ['email' => $email]);
   }

   //helper function for register only tempoary
    public function count_all_employees() {
        $result = $this->query_unique(
            "SELECT COUNT(*) AS cnt FROM employees",
            []  // second argument REQUIRED
        );
        return $result['cnt'];
    }

}
