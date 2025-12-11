<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . "/middleware/AuthMiddleware.php";
Flight::register('auth_middleware', "AuthMiddleware");


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/rest/services/BaseService.php';

require_once __DIR__ . '/rest/services/AuthService.php';
Flight::register('auth_service', "AuthService");

require_once __DIR__ . '/rest/services/EmployeeService.php';
Flight::register('employee_service', 'EmployeeService');

require_once __DIR__ . '/rest/services/StudentService.php';
Flight::register('student_service', 'StudentService');

require_once __DIR__ . '/rest/services/CourseService.php';
Flight::register('course_service', 'CourseService');

require_once __DIR__ . '/rest/services/CourseEnrollmentService.php';
Flight::register('course_enrollment_service', 'CourseEnrollmentService');

require_once __DIR__ . '/rest/services/RoomService.php';
Flight::register('room_service', 'RoomService');

require_once __DIR__ . '/rest/services/RoomAllocationService.php';
Flight::register('room_allocation_service', 'RoomAllocationService');

require_once __DIR__ . '/rest/services/ExamService.php';
Flight::register('exam_service', 'ExamService');

Flight::route('/*', function() {

    $url = Flight::request()->url;

    // Allow home page and login without authentication
    if ($url === '/' || strpos($url, '/auth/login') === 0) {
        return TRUE;
    }

    try {
        $token = Flight::request()->getHeader("Authentication");

        if (!$token) {
            Flight::halt(401, "Missing authentication header");
        }

        $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

        Flight::set('user', $decoded_token->user);
        Flight::set('jwt_token', $token);

        return TRUE;

    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});


require_once __DIR__ . '/rest/routes/EmployeeRoute.php';
require_once __DIR__ . '/rest/routes/StudentRoute.php';
require_once __DIR__ . '/rest/routes/CourseRoute.php';
require_once __DIR__ . '/rest/routes/CourseEnrollmentRoute.php';
require_once __DIR__ . '/rest/routes/RoomRoute.php';
require_once __DIR__ . '/rest/routes/RoomAllocationRoute.php';
require_once __DIR__ . '/rest/routes/ExamRoute.php';
require_once __DIR__ . '/rest/routes/AuthRoute.php';

// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});

Flight::start();  //start FlightPHP
?>