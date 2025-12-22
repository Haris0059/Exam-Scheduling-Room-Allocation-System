<?php
require_once __DIR__ . '/BaseDao.php';

class ExamDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "exams";
        parent::__construct($this->table_name);
    }

    public function getByDate($date)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE date=:date', ['date' => $date]);
    }

    public function getByType($type)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE type=:type', ['type' => $type]);
    }

    public function assignRoom($examId, $roomId) 
    {
        $sql = "
            INSERT INTO exam_rooms (exam_id, room_id)
            VALUES (:exam_id, :room_id)
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'exam_id' => $examId,
            'room_id' => $roomId
        ]);
    }   
}
?>