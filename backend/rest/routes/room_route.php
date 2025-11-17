<?php

/**
 * @OA\Get(
 * path="/rooms",
 * tags={"Rooms"},
 * summary="Get all rooms with pagination",
 * @OA\Parameter(
 * name="search",
 * in="query",
 * description="Search term for room code or type",
 * @OA\Schema(type="string")
 * ),
 * @OA\Parameter(name="offset", in="query", description="Pagination offset", @OA\Schema(type="integer", default=0)),
 * @OA\Parameter(name="limit", in="query", description="Pagination limit", @OA\Schema(type="integer", default=10)),
 * @OA\Parameter(name="order_column", in="query", description="Column to order by", @OA\Schema(type="string", default="id")),
 * @OA\Parameter(name="order_direction", in="query", description="ASC or DESC", @OA\Schema(type="string", default="ASC")),
 * @OA\Response(
 * response=200,
 * description="List of rooms with total count"
 * )
 * )
 */
Flight::route('GET /rooms', function () {
    try {
        $search = Flight::request()->query['search'] ?? '';
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 10;
        $order_column = Flight::request()->query['order_column'] ?? 'id';
        $order_direction = Flight::request()->query['order_direction'] ?? 'ASC';

        $result = Flight::room_service()->getRoomsPaginated(
            $offset,
            $limit,
            $search,
            $order_column,
            $order_direction
        );

        Flight::json($result, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Get(
 * path="/rooms/{id}",
 * tags={"Rooms"},
 * summary="Get a single room by its ID",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the room",
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Room data"),
 * @OA\Response(response=404, description="Room not found")
 * )
 */
Flight::route('GET /rooms/@id', function ($id) {
    try {
        $room = Flight::room_service()->getById($id);
        if (!$room) {
            Flight::json(['error' => 'Room not found'], 404);
        } else {
            Flight::json($room, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Post(
 * path="/rooms",
 * tags={"Rooms"},
 * summary="Add a new room",
 * @OA\RequestBody(
 * required=true,
 * description="Room data",
 * @OA\JsonContent(
 * type="object",
 * required={"code", "type", "seat_capacity", "coord_x", "coord_y", "coord_z"},
 * @OA\Property(property="code", type="integer", example=320),
 * @OA\Property(property="type", type="string", enum={"standard", "it", "lecturehall"}, example="standard"),
 * @OA\Property(property="seat_capacity", type="integer", example=50),
 * @OA\Property(property="coord_x", type="integer", example=10),
 * @OA\Property(property="coord_y", type="integer", example=20),
 * @OA\Property(property="coord_z", type="integer", example=1, description="Floor number")
 * )
 * ),
 * @OA\Response(response=201, description="Room created successfully"),
 * @OA\Response(response=400, description="Invalid data")
 * )
 */
Flight::route('POST /rooms', function () {
    try {
        $data = Flight::request()->data->getData();
        $new_room = Flight::room_service()->addRoom($data);
        Flight::json($new_room, 201);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Put(
 * path="/rooms/{id}",
 * tags={"Rooms"},
 * summary="Update an existing room",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\RequestBody(
 * required=true,
 * description="Room data to update",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="code", type="integer", example="112"),
 * @OA\Property(property="seat_capacity", type="integer", example=55)
 * )
 * ),
 * @OA\Response(response=200, description="Room updated successfully"),
 * @OA\Response(response=404, description="Room not found")
 * )
 */
Flight::route('PUT /rooms/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_room = Flight::room_service()->updateRoom($id, $data);
        
        if (!$updated_room) {
            Flight::json(['error' => 'Room not found'], 404);
        } else {
            Flight::json($updated_room, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Delete(
 * path="/rooms/{id}",
 * tags={"Rooms"},
 * summary="Delete a room",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Room deleted successfully"),
 * @OA\Response(response=404, description="Room not found")
 * )
 */
Flight::route('DELETE /rooms/@id', function ($id) {
    try {
        Flight::room_service()->removeRoom($id);
        Flight::json(['message' => 'Room deleted successfully'], 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


// --- Custom Routes ---

/**
 * @OA\Get(
 * path="/rooms/code/{code}",
 * tags={"Rooms"},
 * summary="Get a room by its code",
 * @OA\Parameter(name="code", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="Room data"),
 * @OA\Response(response=404, description="Room not found")
 * )
 */
Flight::route('GET /rooms/code/@code', function ($code) {
    try {
        $room = Flight::room_service()->getByCode($code);
        if (!$room) {
            Flight::json(['error' => 'Room not found'], 404);
        } else {
            Flight::json($room, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/rooms/type/{type}",
 * tags={"Rooms"},
 * summary="Get a room by its type (Note: DAO uses query_unique)",
 * @OA\Parameter(name="type", in="path", required=true, @OA\Schema(enum={"standard", "it", "lecturehall"})),
 * @OA\Response(response=200, description="Room data"),
 * @OA\Response(response=404, description="Room not found")
 * )
 */
Flight::route('GET /rooms/type/@type', function ($type) {
    try {
        $room = Flight::room_service()->getByType($type);
        if (!$room) {
            Flight::json(['error' => 'Room not found'], 404);
        } else {
            Flight::json($room, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});