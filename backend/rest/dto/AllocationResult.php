<?php
class AllocationResult {
    /** @var ParsedRoom[] */
    public $rooms = [];
    public $cost = 999999; // high default "cost" (e.g., max distance)
    public $multi_floor = false;
    public $total_capacity = 0;
    
    // success/failure status
    public $success = false; 
}
?>