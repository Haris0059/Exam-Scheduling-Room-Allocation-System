<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ExamDao.php';
require_once __DIR__ . '/RoomAllocationService.php';

class ExamService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new ExamDao());
    }

    public function addExam($data)
    {
        $this->validationCheck($data, 'add');
        return $this->dao->add($data);
    }

    public function updateExam($id, $data)
    {
        $this->validationCheck($data, 'update');

        return parent::update($id, $data); 
    }

    public function validationCheck($data, $action = 'add')
    {
        // 1. Check for fields required on BOTH add and update
        if (empty($data['date'])) {
            throw new Exception("Exam date is required.", 400);
        }
        if (empty($data['start'])) {
            throw new Exception("Exam start time is required.", 400);
        }
        if (empty($data['end'])) {
            throw new Exception("Exam end time is required.", 400);
        }
        if (empty($data['type'])) {
            throw new Exception("Exam type is required.", 400);
        }

        // 2. Check for valid formats (both add and update)
        $d = DateTime::createFromFormat('Y-m-d', $data['date']);
        if (!$d || $d->format('Y-m-d') !== $data['date']) {
            throw new Exception("Invalid date format. Use YYYY-MM-DD.", 400);
        }
        
        // 3. Check for valid logic (both add and update)
        if (strtotime($data['start']) >= strtotime($data['end'])) {
            throw new Exception("Exam end time must be after the start time.", 400);
        }

        // 4. Check for fields required ONLY on 'add'
        if ($action == 'add') {
            // MOVED THIS CHECK:
            if (empty($data['course_id'])) {
                throw new Exception("Course ID is required.", 400);
            }
            
            $exam_start_datetime = strtotime($data['date'] . ' ' . $data['start']);
            if ($exam_start_datetime < time()) {
                throw new Exception("Cannot schedule an exam in the past.", 400);
            }
        }
    }

    public function getByDate($date)
    {
        return $this->dao->getByDate($date);
    }

 
    public function getByType($type)
    {
        return $this->dao->getByType($type);
    }

    public function addExamWithAllocation($data)
    {
        $this->validationCheck($data, 'add');

        $allocationService = new RoomAllocationService();

        $conn = $this->dao->getConnection();

        $conn->beginTransaction();

        try {
            $exam = $this->dao->add([
                'course_id' => $data['course_id'],
                'date'      => $data['date'],
                'start'     => $data['start'],
                'end'       => $data['end'],
                'type'      => $data['type']
            ]);

            $allocation = $allocationService->findAllocation(
                $exam['id'],
                $data['room_type']
            );

            foreach ($allocation->rooms as $room) {
                $this->dao->assignRoom($exam['id'], $room->id);
            }

            $conn->commit();

            return [
                'exam'       => $exam,
                'allocation' => $allocation
            ];

        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

}
?>