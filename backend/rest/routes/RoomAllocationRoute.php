<?php

/**
 * @OA\Post(
 * path="/allocate-exam",
 * tags={"Room Allocation"},
 * summary="Finds the best room allocation for a specific exam",
 * description="Runs the core algorithm to find a set of rooms for an exam based on student count and room type.",
 * @OA\RequestBody(
 * required=true,
 * description="Data needed to run the allocation",
 * @OA\JsonContent(
 * type="object",
 * required={"exam_id", "room_type"},
 * @OA\Property(property="exam_id", type="integer", example=1, description="The ID of the exam to be scheduled."),
 * @OA\Property(property="room_type", type="string", example="standard", description="The required room type ('standard', 'it', 'lecturehall').")
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Allocation successful. Returns the set of rooms and cost.",
 * @OA\JsonContent(
 * @OA\Property(property="success", type="boolean", example=true),
 * @OA\Property(property="message", type="string", example="Allocation found."),
 * @OA\Property(property="allocation", ref="#/components/schemas/AllocationResult")
 * )
 * ),
 * @OA\Response(response=400, description="Invalid input (e.g., no exam_id)"),
 * @OA\Response(response=404, description="No rooms available or exam not found"),
 * @OA\Response(response=409, description="Could not find a valid allocation (e.g., not enough capacity)")
 * )
 */
Flight::route('POST /allocate-exam', function () {
    try {
        $data = Flight::request()->data->getData();

        // 1. Get data from the request body
        $exam_id = $data['exam_id'];
        $room_type = $data['room_type'];

        if (empty($exam_id) || empty($room_type)) {
            throw new Exception("exam_id and room_type are required.", 400);
        }

        // 2. Call your service (the "engine")
        $result = Flight::room_allocation_service()->findAllocation($exam_id, $room_type);
        
        // 3. Return the successful result (the AllocationResult DTO)
        Flight::json([
            'success' => true,
            'message' => 'Allocation found.',
            'allocation' => $result
        ], 200);

    } catch (Exception $e) {
        // 4. Catch and return any errors (e.g., "No rooms found", "Exam not found")
        Flight::json([
            'success' => false,
            'message' => $e->getMessage()
        ], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Schema(
 * schema="AllocationResult",
 * title="Allocation Result",
 * description="The result of the allocation algorithm",
 * type="object",
 * @OA\Property(property="rooms", type="array", @OA\Items(ref="#/components/schemas/ParsedRoom")),
 * @OA\Property(property="cost", type="integer", description="The 'cost' of the allocation (lower is better)"),
 * @OA\Property(property="multi_floor", type="boolean", description="True if rooms are on different floors"),
 * @OA\Property(property="total_capacity", type="integer", description="Total capacity of the allocated rooms"),
 * @OA\Property(property="success", type="boolean", description="True if a valid allocation was found")
 * )
 *
 * @OA\Schema(
 * schema="ParsedRoom",
 * title="Parsed Room",
 * type="object",
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="capacity", type="integer"),
 * @OA\Property(property="x", type="integer"),
 * @OA\Property(property="y", type="integer"),
 * @OA\Property(property="z", type="integer", description="Floor number"),
 * @OA\Property(property="distance_from_anchor", type="integer")
 * )
 */