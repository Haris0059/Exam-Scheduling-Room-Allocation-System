<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ExamDao.php';

class ExamService extends BaseService
{
    protected $examDao;

    public function __construct()
    {
        $dao = new ExamDao();
        parent::__construct($dao);
    }

    // Add your service methods here

    public function addExam($data)
    {
        $this->validationCheck($data);

        return $this->dao->add($data);
    }

    public function validationCheck($data)
    {
        $d = DateTime::createFromFormat('Y-m-d', $data['date']);
        $exam_start_datetime = strtotime($data['date'] . ' ' . $data['start']);

        if (empty($data['date'])) {
            throw new Exception("Exam date is required.");
        } if (!$d || $d->format('Y-m-d') !== $data['date']) {
            throw new Exception("Invalid date format.");
        } if (empty($data['start'])) {
            throw new Exception("Exam start time is required.");
        } if (empty($data['end'])) {
            throw new Exception("Exam end time is required.");
        } if (strtotime($data['start']) >= strtotime($data['end'])) {
            throw new Exception("Exam end time must be after the start time.");
        } if ($exam_start_datetime < time()) {
            throw new Exception("Cannot schedule an exam in the past.");
        } if (empty($data['type'])) {
            throw new Exception("Exam type is required.");
        } if (empty($data['course_id'])) {
            throw new Exception("Course is required.");
        }
    }
}
