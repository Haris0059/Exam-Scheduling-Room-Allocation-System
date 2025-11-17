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



    public function findAvailableRoomsByType($date, $start_time, $end_time, $required_room_type) {
        
        $sql = "
            SELECT
                r.id AS room_id,
                r.type,
                r.coord_z AS floor,
                r.seat_capacity,
                r.coord_x,
                r.coord_y
            FROM
                rooms r
            WHERE
                r.type = ?
                AND NOT EXISTS (
                    SELECT 1
                    FROM exam_rooms er
                    JOIN exams e ON er.exam_id = e.id
                    WHERE er.room_id = r.id
                      AND e.date = ?
                      AND (e.start < ? AND e.end > ?)  -- overlap check
                )
            ORDER BY
                r.coord_z, r.seat_capacity DESC;
        ";

        // Parameters for the query in order
        $params = [
            $required_room_type,
            $date,
            $end_time,   // (e.start < $end_time)
            $start_time  // (e.end > $start_time)
        ];

        // This query method should exist in your BaseDao
        return $this->query($sql, $params);
    }
}
?>