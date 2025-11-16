<?php
// Require all the DAOs and DTOs you'll need
require_once __DIR__ . '/../dao/RoomDao.php';
require_once __DIR__ . '/../dao/ExamDao.php';
require_once __DIR__ . '/../dao/CourseEnrollmentDao.php';
require_once __DIR__ . '/../dto/ParsedRoom.php';
require_once __DIR__ . '/../dto/AllocationResult.php';

class RoomAllocationService {
    
    private $roomDao;
    private $examDao;
    private $courseEnrollmentDao;
    
    public function __construct() {
        $this->roomDao = new RoomDao();
        $this->examDao = new ExamDao();
        $this->courseEnrollmentDao = new CourseEnrollmentDao();
    }

    /**
     * Finds the best room allocation for an exam.
     * This is the main public method you will call from your API.
     */
    public function findAllocation($exam_id, $required_room_type) {
        
        // 1. Get Requirements
        $exam = $this->examDao->getById($exam_id);
        if (!$exam) throw new Exception("Exam not found.", 404);

        $required_capacity = $this->courseEnrollmentDao->getCountByCourse($exam['course_id']);
        if ($required_capacity == 0) throw new Exception("No students enrolled in this course.", 400);

        // 2. Get Available Rooms
        $raw_rooms = $this->roomDao->findAvailableRoomsByType(
            $exam['date'], 
            $exam['start'],
            $exam['end'],
            $required_room_type
        );
        
        if (empty($raw_rooms)) {
            throw new Exception("No rooms of type '{$required_room_type}' are available at this time.", 404);
        }

        // 3. Process Rooms (This function does the grouping/summing)
        $rooms_by_floor = $this->parseAndGroupRooms($raw_rooms);

        // 4. Find Best "Same-Floor" Solution
        $best_solution = $this->findBestSameFloorSolution(
            $rooms_by_floor, 
            $required_capacity
        );

        if ($best_solution->success) {
            return $best_solution; // SUCCESS: Found a same-floor solution
        }
        
        // 5. Fallback: No single floor worked. Try multi-floor.
        $multi_floor_solution = $this->findMultiFloorAllocation(
            $rooms_by_floor, 
            $required_capacity
        );
        
        if ($multi_floor_solution->success) {
            return $multi_floor_solution; // SUCCESS: Found a multi-floor solution
        }

        // 6. Failure: No solution found
        throw new Exception("Could not find any combination of rooms with enough capacity.", 409);
    }
    
    

    /**
     * Helper to group rooms by floor and sum their capacity.
     * This is where the PHP logic starts.
     */
    private function parseAndGroupRooms($raw_rooms) {
        $temp_grouping = [];
        $floor_capacities = [];

        // 1. First pass: Group rooms and calculate capacities
        foreach ($raw_rooms as $row) {
            $floor = $row['floor'];

            if (!isset($temp_grouping[$floor])) {
                $temp_grouping[$floor] = [];
                $floor_capacities[$floor] = 0;
            }
            
            // Use the ParsedRoom DTO
            $temp_grouping[$floor][] = new ParsedRoom($row);
            $floor_capacities[$floor] += (int)$row['seat_capacity'];
        }

        // 2. Second pass: Build the final structure
        $final_structure = [];
        foreach ($temp_grouping as $floor => $rooms) {
            $final_structure[$floor] = [
                'total_capacity' => $floor_capacities[$floor],
                'rooms' => $rooms
            ];
        }
        
        return $final_structure;
    }

    /**
     * Tries to find the best single-floor solution.
     */
    private function findBestSameFloorSolution($rooms_by_floor, $required_capacity) {
        $best_solution = new AllocationResult();

        foreach ($rooms_by_floor as $floor => $data) {
            // 1. Check if this floor is even a possibility
            if ($data['total_capacity'] >= $required_capacity) {
                
                // 2. Find the best cluster of rooms on this floor
                $candidate_solution = $this->findBestClusterOnFloor(
                    $data['rooms'], 
                    $required_capacity
                );
                
                // 3. Keep it if it's the "best" (lowest cost) so far
                if ($candidate_solution->cost < $best_solution->cost) {
                    $best_solution = $candidate_solution;
                }
            }
        }
        
        if ($best_solution->cost < 999999) { // 999999 is our default "no solution" cost
             $best_solution->success = true;
        }

        return $best_solution;
    }

    /**
     * Heuristic to find the "best" cluster of rooms on a single floor.
     */
    private function findBestClusterOnFloor($rooms_on_floor, $required_capacity) {
        $result = new AllocationResult();
        
        // 1. Pick an "Anchor" room (the largest one, which is first in the list)
        $anchor_room = $rooms_on_floor[0];

        // 2. Calculate distance of all other rooms from this anchor
        foreach ($rooms_on_floor as $room) {
            $room->distance_from_anchor = $this->manhattanDistance($anchor_room, $room);
        }

        // 3. Sort rooms by distance from anchor (closest first)
        usort($rooms_on_floor, fn($a, $b) => $a->distance_from_anchor <=> $b->distance_from_anchor);

        // 4. Greedily add rooms until capacity is met
        foreach ($rooms_on_floor as $room) {
            $result->rooms[] = $room;
            $result->total_capacity += $room->capacity;
            $result->cost = $room->distance_from_anchor; // Cost is the distance of the *farthest* room

            if ($result->total_capacity >= $required_capacity) {
                break; // We have enough capacity
            }
        }
        
        return $result;
    }

    /**
     * Fallback logic to find a multi-floor solution.
     */
    private function findMultiFloorAllocation($rooms_by_floor, $required_capacity) {
        $result = new AllocationResult();
        $result->multi_floor = true;

        // 1. Sort floors by their total capacity (largest first)
        uasort($rooms_by_floor, fn($a, $b) => $b['total_capacity'] <=> $a['total_capacity']);

        $capacity_needed = $required_capacity;

        foreach ($rooms_by_floor as $floor => $data) {
            // 2. Greedily add rooms from each floor (largest first)
            foreach ($data['rooms'] as $room) {
                $result->rooms[] = $room;
                $result->total_capacity += $room->capacity;
                $capacity_needed -= $room->capacity;

                // 3. Check if we are done
                if ($capacity_needed <= 0) {
                    $result->success = true;
                    $result->cost = 10000; // Assign a high cost for multi-floor
                    return $result;
                }
            }
        }
        
        // If we get here, we're still short on capacity
        $result->success = false;
        return $result;
    }

    /**
     * Calculates Manhattan distance (good for grid-like layouts).
     * Assumes rooms are on the same floor.
     */
    private function manhattanDistance(ParsedRoom $roomA, ParsedRoom $roomB) {
        return abs($roomA->x - $roomB->x) + abs($roomA->y - $roomB->y);
    }
}
?>