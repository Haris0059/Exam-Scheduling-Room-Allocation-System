<?php
require 'vendor/autoload.php'; //run autoloader


// Default route
Flight::route('/', function(){  
   echo 'Exam Scheduling and Room Allocation System API is running!';
});


Flight::start();  //start FlightPHP
?>