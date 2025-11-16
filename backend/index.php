<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/rest/services/BaseService.php';

require_once __DIR__ . '/rest/services/CourseEnrollmentService.php';
Flight::register('courseEnrollmentService', 'CourseEnrollmentService');

require_once __DIR__ . '/rest/services/CourseService.php';
Flight::register('courseService', 'CourseService');

require_once __DIR__ . '/rest/services/EmployeeService.php';
Flight::register('employeeService', 'EmployeeService');

require_once __DIR__ . '/rest/services/ExamService.php';
Flight::register('examService', 'ExamService');

require_once __DIR__ . '/rest/services/RoomAllocationService.php';
Flight::register('roomAllocationService', 'RoomAllocationService');

require_once __DIR__ . '/rest/services/RoomService.php';
Flight::register('roomService', 'RoomService');

require_once __DIR__ . '/rest/services/StudentService.php';
Flight::register('studentService', 'StudentService');

require_once __DIR__ . '/rest/routes/CourseEnrollmentRoute.php';
require_once __DIR__ . '/rest/routes/CourseRoute.php';
require_once __DIR__ . '/rest/routes/EmployeeRoute.php';
require_once __DIR__ . '/rest/routes/ExamRoute.php';
require_once __DIR__ . '/rest/routes/RoomAllocationRoute.php';
require_once __DIR__ . '/rest/routes/RoomRoute.php';
require_once __DIR__ . '/rest/routes/StudentRoute.php';


// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});

// Add this line to tell Flight its base path
Flight::set('flight.base_url', '/Exam-Scheduling-Room-Allocation-System/backend');

Flight::start();  //start FlightPHP