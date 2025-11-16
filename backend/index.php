<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/rest/services/BaseService.php';

require_once __DIR__ . '/rest/services/CourseEnrollmentService.php';
Flight::register('course_enrollment_service', 'CourseEnrollmentService');

require_once __DIR__ . '/rest/services/CourseService.php';
Flight::register('course_service', 'CourseService');

require_once __DIR__ . '/rest/services/EmployeeService.php';
Flight::register('employee_service', 'EmployeeService');

require_once __DIR__ . '/rest/services/ExamService.php';
Flight::register('exam_service', 'ExamService');

require_once __DIR__ . '/rest/services/RoomAllocationService.php';
Flight::register('room_allocation_service', 'RoomAllocationService');

require_once __DIR__ . '/rest/services/RoomService.php';
Flight::register('room_service', 'RoomService');

require_once __DIR__ . '/rest/services/StudentService.php';
Flight::register('student_service', 'StudentService');

require_once __DIR__ . '/rest/routes/course_enrollment_route.php';
require_once __DIR__ . '/rest/routes/course_route.php';
require_once __DIR__ . '/rest/routes/employee_route.php';
require_once __DIR__ . '/rest/routes/exam_route.php';
require_once __DIR__ . '/rest/routes/room_allocation_route.php';
require_once __DIR__ . '/rest/routes/room_route.php';
require_once __DIR__ . '/rest/routes/student_route.php';


// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});


Flight::start();  //start FlightPHP
?>