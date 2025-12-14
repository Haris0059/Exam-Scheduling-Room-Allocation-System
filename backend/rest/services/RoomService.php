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

    private function validateRoomData($data)
    {
        // code
        if (!isset($data['code']) || !is_numeric($data['code'])) {
            throw new Exception("Room code is required and must be a number.");
        }
        if ((int)$data['code'] < 0 || strlen((string)$data['code']) > 3) {
            throw new Exception("Room code must be a numeric value with max 3 digits.");
        }

        // type
        if (isset($data['type']) && !in_array($data['type'], ['standard', 'it', 'lecturehall'])) {
            throw new Exception("Invalid room type. Allowed values: standard, it, lecturehall.");
        }

        // seat_capacity
        if (!isset($data['seat_capacity']) || !is_numeric($data['seat_capacity'])) {
            throw new Exception("Seat capacity is required and must be a number.");
        }
        if ((int)$data['seat_capacity'] <= 0) {
            throw new Exception("Seat capacity must be greater than 0.");
        }

        // coordinates
        if (!isset($data['coord_x']) || !is_numeric($data['coord_x'])) {
            throw new Exception("Coordinate X must be a valid number.");
        }
        if (!isset($data['coord_y']) || !is_numeric($data['coord_y'])) {
            throw new Exception("Coordinate Y must be a valid number.");
        }
        if (!isset($data['coord_z']) || !is_numeric($data['coord_z'])) {
            throw new Exception("Coordinate Z must be a valid number.");
        }
    }

    private function validateRoomUpdate($id, $data)
    {
        // id
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid Room ID.");
        }

        if (!$this->dao->getById($id)) {
            throw new Exception("Room with the given ID does not exist.");
        }

        // reuse same rules
        $this->validateRoomData($data);
    }


    public function addRoom($data)
    {
        $this->validateRoomData($data);
        return $this->dao->add($data);
    }

    public function updateRoom($id, $data)
    {
        $this->validateRoomUpdate($id, $data);
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
        if (!is_numeric($code)) {
            throw new Exception("Room code must be numeric.");
        }

        return $this->dao->getByCode($code);
    }

    public function getByType($type)
    {
        if (!in_array($type, ['standard', 'it', 'lecturehall'])) {
            throw new Exception("Invalid room type.");
        }

        return $this->dao->getByType($type);
    }
    
    public function getBySeatCapacity($seat_capacity)
    {
        if (!is_numeric($seat_capacity) || $seat_capacity <= 0) {
            throw new Exception("Seat capacity must be a positive number.");
        }

        return $this->dao->getBySeatCapacity($seat_capacity);
    }
}
?>