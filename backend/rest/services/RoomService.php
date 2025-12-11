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

        return $this->dao->add($data);
    }

    public function updateRoom($id, $data)
    {
        //$this->validationCheck($data);
        
        return $this->dao->update($data, $id);
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
        } else if (empty($data['coord_x']) || empty($data['coord_y']) || empty($data['coord_z'])) {
            throw new Exception("Coordinates are required.");
        }
    }

    public function getRoomsPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->dao->countRoomsPaginated($search)['count'];
        $data = $this->dao->getRoomsPaginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'total_records' => $count,
            'data' => $data
        ];
    }

    public function getByCode($code)
    {
        return $this->dao->getByCode($code);
    }

    public function getByType($type)
    {
        return $this->dao->getByType($type);
    }
    
    public function getBySeatCapacity($seat_capacity)
    {
        return $this->dao->getBySeatCapacity($seat_capacity);
    }
}
?>