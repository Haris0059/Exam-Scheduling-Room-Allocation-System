<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
header("Access-Control-Allow-Origin: https://esras-app-5ejka.ondigitalocean.app"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

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

require_once __DIR__ . '/rest/services/FacultyService.php';
Flight::register('faculty_service', 'FacultyService');

require_once __DIR__ . '/rest/services/DepartmentService.php';
Flight::register('department_service', 'DepartmentService');

Flight::route('/*', function () {

    error_log("MIDDLEWARE HIT: " . Flight::request()->url);

    $url = Flight::request()->url;

    if (
        strpos($url, '/auth/login') !== false ||
        strpos($url, '/auth/set-password') !== false
    ) {
        error_log("MIDDLEWARE BYPASS");
        return TRUE;
    }

    error_log("MIDDLEWARE BLOCK");

    Flight::halt(401, "Blocked by middleware");
});



require_once __DIR__ . '/rest/routes/EmployeeRoute.php';
require_once __DIR__ . '/rest/routes/StudentRoute.php';
require_once __DIR__ . '/rest/routes/CourseRoute.php';
require_once __DIR__ . '/rest/routes/CourseEnrollmentRoute.php';
require_once __DIR__ . '/rest/routes/RoomRoute.php';
require_once __DIR__ . '/rest/routes/RoomAllocationRoute.php';
require_once __DIR__ . '/rest/routes/ExamRoute.php';
require_once __DIR__ . '/rest/routes/AuthRoute.php';
require_once __DIR__ . '/rest/routes/FacultyRoute.php';
require_once __DIR__ . '/rest/routes/DepartmentRoute.php';

// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});

Flight::route('OPTIONS /*', function() {
    header("Access-Control-Allow-Origin: https://esras-app-5ejka.ondigitalocean.app");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    exit;
});

Flight::start();  //start FlightPHP
?>