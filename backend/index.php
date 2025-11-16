<?php
require 'vendor/autoload.php'; //run autoloader

require_once __DIR__ . '/rest/services/CourseEnrollmentService.php';
Flight::register('courseEnrollmentService', 'CourseEnrollmentService');
require_once __DIR__ . '/rest/routes/CourseEnrollmentRoutes.php';

// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});


Flight::start();  //start FlightPHP
?>