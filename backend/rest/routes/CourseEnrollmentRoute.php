<?php

/**
 * @OA\Get(
 * path="/enrollments/course/{id}/count",
 * tags={"enrollments"},
 * summary="Get the count of active students in a course",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="The Course ID",
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns the total count of active students"
 * )
 * )
 */
Flight::route('GET /enrollments/course/@id/count', function ($id) {
    try {
        $count = Flight::courseEnrollmentService()->getCountByCourse($id);
        
        // Return a clean JSON object, not just a number
        Flight::json([
            'course_id' => (int)$id,
            'active_students' => $count
        ], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});


/**
 * @OA\Post(
 * path="/enrollments",
 * tags={"enrollments"},
 * summary="Enroll a student in a course",
 * @OA\RequestBody(
 * required=true,
 * description="Enrollment data",
 * @OA\JsonContent(
 * type="object",
 * required={"student_id", "course_id", "status"},
 * @OA\Property(property="student_id", type="integer", example=1),
 * @OA\Property(property="course_id", type="integer", example=101),
 * @OA\Property(property="status", type="string", example="active")
 * )
 * ),
 * @OA\Response(response=201, description="Enrollment created"),
 * @OA\Response(response=400, description="Invalid data")
 * )
 */
Flight::route('POST /enrollments', function () {
    try {
        $data = Flight::request()->data->getData();
        $new_enrollment = Flight::courseEnrollmentService()->create($data);
        Flight::json($new_enrollment, 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Get(
 * path="/enrollments",
 * tags={"enrollments"},
 * summary="Get all enrollments",
 * @OA\Response(response=200, description="List of all enrollments")
 * )
 */
Flight::route('GET /enrollments', function () {
    try {
        $enrollments = Flight::courseEnrollmentService()->get_all();
        Flight::json($enrollments, 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});


/**
 * @OA\Put(
 * path="/enrollments/{id}",
 * tags={"enrollments"},
 * summary="Update an enrollment (e.g., set status to 'dropped')",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="status", type="string", example="dropped")
 * )
 * ),
 * @OA\Response(response=200, description="Enrollment updated"),
 * @OA\Response(response=404, description="Enrollment not found")
 * )
 */
Flight::route('PUT /enrollments/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_enrollment = Flight::courseEnrollmentService()->update($id, $data);
        
        if (!$updated_enrollment) {
            Flight::json(['error' => 'Enrollment not found'], 404);
        } else {
            Flight::json($updated_enrollment, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Delete(
 * path="/enrollments/{id}",
 * tags={"enrollments"},
 * summary="Delete an enrollment",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="Enrollment deleted"),
 * @OA\Response(response=444, description="Enrollment not found")
 * )
 */
Flight::route('DELETE /enrollments/@id', function ($id) {
    try {
        Flight::courseEnrollmentService()->delete($id);
        Flight::json(['message' => 'Enrollment deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});