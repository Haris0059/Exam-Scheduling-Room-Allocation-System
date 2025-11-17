<?php
class ParsedRoom {
    public $id;
    public $capacity;
    public $x;
    public $y;
    public $z; // This is the floor number
    public $distance_from_anchor = 0;

    public function __construct($db_row) {
        $this->id = (int)$db_row['room_id'];
        $this->capacity = (int)$db_row['seat_capacity'];
        $this->x = (int)$db_row['coord_x'];
        $this->y = (int)$db_row['coord_y'];
        $this->z = (int)$db_row['floor'];
    }
}
?>