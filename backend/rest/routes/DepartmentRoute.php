<?php

/**
 * @OA\Get(
 *   path="/departments/byFaculty/{id}",
 *   tags={"Departments"},
 *   summary="Get departments by faculty",
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="Faculty ID",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="List of departments"
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid faculty ID"
 *   )
 * )
 */
Flight::route('GET /departments/byFaculty/@id', function ($id) {
    try {
        $departments = Flight::department_service()->getByFaculty($id);
        Flight::json(['data' => $departments], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});
