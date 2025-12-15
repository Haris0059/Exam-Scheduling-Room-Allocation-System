<?php

/**
 * @OA\Get(
 * path="/faculty",
 * tags={"Faculty"},
 * summary="Get all faculties",
 * @OA\Response(
 * response=200,
 * description="List of faculties"
 * )
 * )
 */
Flight::route('GET /faculty', function () {
    try {
        $faculties = Flight::faculty_service()->getAll();
        Flight::json(['data' => $faculties], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 * path="/faculty/{id}",
 * tags={"Faculty"},
 * summary="Get a faculty by ID",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="Faculty ID",
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Faculty data"),
 * @OA\Response(response=404, description="Faculty not found")
 * )
 */
Flight::route('GET /faculty/@id', function ($id) {
    try {
        $faculty = Flight::faculty_service()->getById($id);

        if (!$faculty) {
            Flight::json(['error' => 'Faculty not found'], 404);
        } else {
            Flight::json($faculty, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Post(
 * path="/faculty",
 * tags={"Faculty"},
 * summary="Create a new faculty",
 * @OA\RequestBody(
 * required=true,
 * description="Faculty data",
 * @OA\JsonContent(
 * type="object",
 * required={"name"},
 * @OA\Property(property="name", type="string", example="Faculty of Engineering")
 * )
 * ),
 * @OA\Response(response=201, description="Faculty created successfully"),
 * @OA\Response(response=400, description="Invalid data")
 * )
 */
Flight::route('POST /faculty', function () {
    try {
        $data = Flight::request()->data->getData();
        $faculty = Flight::faculty_service()->add($data);
        Flight::json($faculty, 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Put(
 * path="/faculty/{id}",
 * tags={"Faculty"},
 * summary="Update an existing faculty",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\RequestBody(
 * required=true,
 * description="Faculty data to update",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="name", type="string", example="Updated Faculty Name")
 * )
 * ),
 * @OA\Response(response=200, description="Faculty updated successfully"),
 * @OA\Response(response=404, description="Faculty not found")
 * )
 */
Flight::route('PUT /faculty/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $faculty = Flight::faculty_service()->update($id, $data);

        if (!$faculty) {
            Flight::json(['error' => 'Faculty not found'], 404);
        } else {
            Flight::json($faculty, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Delete(
 * path="/faculty/{id}",
 * tags={"Faculty"},
 * summary="Delete a faculty",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Faculty deleted successfully"),
 * @OA\Response(response=404, description="Faculty not found")
 * )
 */
Flight::route('DELETE /faculty/@id', function ($id) {
    try {
        Flight::faculty_service()->delete($id);
        Flight::json(['message' => 'Faculty deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});
