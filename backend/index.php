<?php
require 'vendor/autoload.php'; //run autoloader

// All services
require_once 'rest/services/BaseService.php';
require_once 'rest/services/CourseService.php';
require_once 'rest/services/EmployeeService.php';
require_once 'rest/services/ExamService.php';
require_once 'rest/services/RoomAllocationService.php';
require_once 'rest/services/RoomService.php';
require_once 'rest/services/StudentService.php';

// All routes
//require_once 'rest/routes/BaseRoute.php';
require_once 'rest/routes/CourseRoute.php';
//require_once 'rest/routes/EmployeeRoute.php';
//require_once 'rest/routes/ExamRoute.php';
//require_once 'rest/routes/RoomAllocationRoute.php';
//require_once 'rest/routes/RoomRoute.php';
//require_once 'rest/routes/StudentRoute.php';

// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});


Flight::start();  //start FlightPHP
?>