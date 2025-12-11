<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {
   private $auth_dao;
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
   }


   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }


   public function register($entity) {  
    if (empty($entity['email']) || empty($entity['password'])) {
        return ['success' => false, 'error' => 'Email and password are required.'];
    }

    // Check if the email is already registered
    $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
    if ($email_exists) {
        return ['success' => false, 'error' => 'Email already registered.'];
    }

    // Assign admin role ONLY if this is the first account
    $count = $this->auth_dao->count_all_employees();
    if ($count == 0) {
        $entity['role'] = 'admin';
        $entity['faculty_id'] = '1';
    } else {
        $entity['role'] = 'employee';   // or "assistant", "professor", etc.
    }

    // Hash password
    $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

    // Insert into DB
    $entity = parent::add($entity);

    // Do not return password
    unset($entity['password']);

    return ['success' => true, 'data' => $entity];
}



   public function login($entity) {  
       if (empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }


       $user = $this->auth_dao->get_user_by_email($entity['email']);
       if(!$user){
           return ['success' => false, 'error' => 'Invalid username or password.'];
       }


       if(!$user || !password_verify($entity['password'], $user['password']))
           return ['success' => false, 'error' => 'Invalid username or password.'];


       unset($user['password']);
      
       $jwt_payload = [
           'user' => $user,
           'iat' => time(),
           // If this parameter is not set, JWT will be valid for life. This is not a good approach
           'exp' => time() + (60 * 60 * 24) // valid for day
       ];


       $token = JWT::encode(
           $jwt_payload,
           Config::JWT_SECRET(),
           'HS256'
       );


       return ['success' => true, 'data' => array_merge($user, ['token' => $token])];             
   }

}
