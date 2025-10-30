<?php

require_once __DIR__ . '/dao/StudentDao.php';

$student_dao = new StudentDao();
$students = $student_dao->get_all();
print_r($students);
$student = $student_dao->get_by_id(1);
print_r($student);
$student_dao->add(["name" => "James", "email" => "james@gmail.com"]);
$student_dao->update(["name" => "James", "email" => "jamesupdate@gmail.com"], 3);
$student_dao->delete(3);
print_r($student_dao->get_all());