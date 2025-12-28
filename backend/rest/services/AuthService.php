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

    // CURRENT USER
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