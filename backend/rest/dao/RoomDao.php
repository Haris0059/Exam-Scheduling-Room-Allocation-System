<?php
require_once __DIR__ . '/BaseDao.php';

class RoomDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "rooms";
        parent::__construct($this->table_name);
    }

    public function getByCode($code)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE code=:code', ['code' => $code]);
    }

    public function getByType($type)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE type=:type', ['type' => $type]);
    }

    public function getBySeatCapacity($seat_capacity)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE seat_capacity=:seat_capacity', ['seat_capacity' => $seat_capacity]);
    }

    public function getByCoordinates($coordinates)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE coordinates=:coordinates', ['coordinates' => $coordinates]);
    }

    public function countRoomsPaginated($search)
    {
        $query = "SELECT COUNT(*) AS count
                  FROM " . $this->table_name . "
                  WHERE LOWER(code) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(type) LIKE CONCAT('%', :search, '%');";
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function getRoomsPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE LOWER(code) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(type) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        return $this->query($query, [
            'search' => $search
        ]);
    }
}