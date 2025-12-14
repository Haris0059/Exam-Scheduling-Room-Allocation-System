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

        // FIRST LOGIN → PASSWORD NOT SET
        if ($user['password'] === NULL) {
            return [
                'status' => 'password_required',
                'data' => [
                    'user_id' => $user['id']
                ]
            ];
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

        return [
            'status' => 'ok',
            'data' => [
                'token' => $token
            ]
        ];
    }

    // SET PASSWORD (FIRST LOGIN ONLY)
    public function set_password($entity) {

        if (empty($entity['user_id']) || empty($entity['password'])) {
            throw new Exception("Invalid request");
        }

        if (!$this->auth_dao->is_password_null($entity['user_id'])) {
            throw new Exception("Password already set");
        }

        $hashed = password_hash($entity['password'], PASSWORD_BCRYPT);

        $this->auth_dao->set_password($entity['user_id'], $hashed);

        $user = $this->auth_dao->get_user_by_id($entity['user_id']);
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

        return [
            'status' => 'ok',
            'data' => [
                'token' => $token
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
        return $user;
    }

}
