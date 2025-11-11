<?php
require 'vendor/autoload.php'; //run autoloader


Flight::route('/', function(){  //define route and define function to handle request
   echo 'Exam Scheduling and Room Allocation System API is running!';
});


Flight::start();  //start FlightPHP
?>
