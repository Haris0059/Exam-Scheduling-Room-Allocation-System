<?php

require_once __DIR__ . '/dao/StudentDao.php';

$student_dao = new StudentDao();
$students = $student_dao->get_all();
print_r($students);
$student = $student_dao->get_by_id(1);
print_r($student);
$student_dao->add(["first_name" => "James", "last_name" => "Smith", "email" => "james.smith@gmail.com", "password" => "password123", "academic_level" => "bachelor"]);
$student_dao->update(["first_name" => "James", "last_name" => "Smith", "email" => "jamesupdate@gmail.com", "password" => "newpassword123", "academic_level" => "master"], 3);
$student_dao->delete(3);
print_r($student_dao->get_all());