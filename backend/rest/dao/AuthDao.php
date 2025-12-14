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

    // FIRST LOGIN CHECK
    public function is_password_null($user_id) {
        $sql = "SELECT password FROM employees WHERE id = :id";
        $user = $this->query_unique($sql, ['id' => $user_id]);
        return empty($user['password']);
    }

    // SET PASSWORD (ONLY ONCE)
    public function set_password($user_id, $hashed_password) {
        $sql = "UPDATE employees SET password = :password WHERE id = :id";
        return $this->query($sql, [
            'password' => $hashed_password,
            'id' => $user_id
        ]);
    }
}
