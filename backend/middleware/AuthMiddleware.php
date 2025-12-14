<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function verifyToken() {

        $authHeader = Flight::request()->getHeader('Authorization');
        if (!$authHeader) {
            Flight::halt(401, "Missing Authorization header");
        }

        if (!str_starts_with($authHeader, 'Bearer ')) {
            Flight::halt(401, "Invalid Authorization header");
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode(
                $token,
                new Key(Config::JWT_SECRET(), 'HS256')
            );
        } catch (Exception $e) {
            Flight::halt(401, "Invalid or expired token");
        }

        // JWT payload has id + role
        Flight::set('user_id', $decoded->id);
        Flight::set('role', $decoded->role);
        Flight::set('jwt_token', $token);

        return TRUE;
    }

    public function authorizeRole($requiredRole) {
        if (Flight::get('role') !== $requiredRole) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles(array $roles) {
        if (!in_array(Flight::get('role'), $roles, true)) {
            Flight::halt(403, 'Forbidden: role not allowed');
        }
    }
}
