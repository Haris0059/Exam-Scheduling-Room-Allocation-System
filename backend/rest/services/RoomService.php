<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/RoomDao.php';

class RoomService extends BaseService
{
    protected $roomDao;

    public function __construct()
    {
        $dao = new RoomDao();

        parent::__construct($dao);
    }

    public function addRoom($data)
    {
        $this->validationCheck($data);

        return $this->dao->insert($data);
    }

    public function updateRoom($id, $data)
    {
        $this->validationCheck($data);
        
        return $this->dao->update($id, $data);
    }

    public function removeRoom($id)
    {
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid Room ID provided.");
        } else if (!$this->dao->getById($id)) {
            throw new Exception("Room with the given ID does not exist.");
        }

        return $this->dao->delete($id);
    }

    public function validationCheck($data) {
        if (empty($data['code'])) {
            throw new Exception("Room code is required.");
        } else if (empty($data['type'])) {
            throw new Exception("Room type is required.");
        } else if (empty($data['seat_capacity'])) {
            throw new Exception("Seat capacity is required.");
        } else if (empty($data['coordinates'])) {
            throw new Exception("Coordinates are required.");
        }
    }
}