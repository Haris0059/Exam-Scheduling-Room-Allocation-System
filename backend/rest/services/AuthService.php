<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {
    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao();
    }

    /**
     * REGISTER NEW EMPLOYEE (Admin creates employee with password)
     */
    public function register($entity) {
        // 1. Validate required fields
        if (empty($entity['email'])) {
            throw new Exception("Email is required");
        }

        if (empty($entity['password'])) {
            throw new Exception("Password is required");
        }

        if (empty($entity['first_name'])) {
            throw new Exception("First name is required");
        }

        if (empty($entity['last_name'])) {
            throw new Exception("Last name is required");
        }

        if (empty($entity['role'])) {
            throw new Exception("Role is required");
        }

        if (empty($entity['faculty_id'])) {
            throw new Exception("Faculty is required");
        }

        if (empty($entity['department_id'])) {
            throw new Exception("Department is required");
        }

        // 2. Validate password strength
        if (strlen($entity['password']) < 6) {
            throw new Exception("Password must be at least 6 characters long");
        }

        // 3. Check if email already exists
        $existingUser = $this->auth_dao->get_user_by_email($entity['email']);
        if ($existingUser) {
            throw new Exception("Email already exists");
        }

        // 4. Hash the password
        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

        // 5. Insert into database
        try {
            $id = $this->auth_dao->add($entity);
            
            if (!$id) {
                throw new Exception("Failed to create employee");
            }
            
            return ['id' => $id, 'message' => 'Employee registered successfully'];
            
        } catch (Exception $e) {
            throw new Exception("Failed to create employee: " . $e->getMessage());
        }
    }

    /**
     * LOGIN
     */
    public function login($entity) {
        if (empty($entity['email'])) {
            throw new Exception("Email is required");
        }

        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if (!$user) {
            throw new Exception("Invalid email or password");
        }

        if (empty($entity['password']) ||
            !password_verify($entity['password'], $user['password'])) {
            throw new Exception("Invalid email or password");
        }

        unset($user['password']);

        $payload = [
            'id'   => $user['id'],
            'role' => $user['role'],
            'iat'  => time(),
            'exp'  => time() + (60 * 60 * 24)
        ];

        $token = JWT::encode(
            $payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        // FIX: Combine first_name and last_name into name
        $full_name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: null;

        return [
            'status' => 'ok',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $full_name,
                    'first_name' => $user['first_name'] ?? null,
                    'last_name' => $user['last_name'] ?? null,
                    'role' => $user['role']
                ]
            ]
        ];
    }

    /**
     * GET CURRENT USER
     */
    public function get_current_user() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            throw new Exception("Missing Authorization header");
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        $decoded = JWT::decode(
            $token,
            new Key(Config::JWT_SECRET(), 'HS256')
        );

        $user = $this->auth_dao->get_user_by_id($decoded->id);
        if (!$user) {
            throw new Exception("User not found");
        }

        unset($user['password']);
        
        $user['name'] = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: null;
        
        return $user;
    }
}
?>