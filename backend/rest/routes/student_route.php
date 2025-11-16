<?php

/**
 * @OA\Get(
 * path="/students",
 * tags={"Students"},
 * summary="Get all students with pagination",
 * @OA\Parameter(
 * name="search",
 * in="query",
 * description="Search term for first name, last name, or email",
 * @OA\Schema(type="string")
 * ),
 * @OA\Parameter(name="offset", in="query", description="Pagination offset", @OA\Schema(type="integer", default=0)),
 * @OA\Parameter(name="limit", in="query", description="Pagination limit", @OA\Schema(type="integer", default=10)),
 * @OA\Parameter(name="order_column", in="query", description="Column to order by", @OA\Schema(type="string", default="id")),
 * @OA\Parameter(name="order_direction", in="query", description="ASC or DESC", @OA\Schema(type="string", default="ASC")),
 * @OA\Response(
 * response=200,
 * description="List of students with total count"
 * )
 * )
 */
Flight::route('GET /students', function () {
    try {
        $search = Flight::request()->query['search'] ?? '';
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 10;
        $order_column = Flight::request()->query['order_column'] ?? 'id';
        $order_direction = Flight::request()->query['order_direction'] ?? 'ASC';

        $result = Flight::student_service()->getStudentsPaginated(
            $offset,
            $limit,
            $search,
            $order_column,
            $order_direction
        );

        Flight::json($result, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Get(
 * path="/students/{id}",
 * tags={"Students"},
 * summary="Get a single student by their ID",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the student",
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Student data"),
 * @OA\Response(response=404, description="Student not found")
 * )
 */
Flight::route('GET /students/@id', function ($id) {
    try {
        $student = Flight::student_service()->getById($id);
        if (!$student) {
            Flight::json(['error' => 'Student not found'], 404);
        } else {
            Flight::json($student, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Post(
 * path="/students",
 * tags={"Students"},
 * summary="Add a new student (status is set to 'active' automatically)",
 * @OA\RequestBody(
 * required=true,
 * description="Student data",
 * @OA\JsonContent(
 * type="object",
 * required={"first_name", "last_name", "email", "academic_level", "semester"},
 * @OA\Property(property="first_name", type="string", example="John"),
 * @OA\Property(property="last_name", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", example="john.doe@test.com"),
 * @OA\Property(property="academic_level", type="string", enum={"bachelor", "master", "doctorate"}, example="bachelor"),
 * @OA\Property(property="semester", type="integer", example=3),
 * @OA\Property(property="department_id", type="integer", example=1)
 * )
 * ),
 * @OA\Response(response=201, description="Student created successfully"),
 * @OA\Response(response=400, description="Invalid data")
 * )
 */
Flight::route('POST /students', function () {
    try {
        $data = Flight::request()->data->getData();
        $new_student = Flight::student_service()->add($data);
        Flight::json($new_student, 201);
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Put(
 * path="/students/{id}",
 * tags={"Students"},
 * summary="Update an existing student",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\RequestBody(
 * required=true,
 * description="Student data to update",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="first_name", type="string", example="Jane"),
 * @OA\Property(property="email", type="string", example="jane.doe@test.com"),
 * @OA\Property(property="academic_level", type="string", enum={"bachelor", "master", "doctorate"}),
 * @OA\Property(property="semester", type="integer", example=4),
 * @OA\Property(property="status", type="string", enum={"active", "inactive"})
 * )
 * ),
 * @OA\Response(response=200, description="Student updated successfully"),
 * @OA\Response(response=404, description="Student not found")
 * )
 */
Flight::route('PUT /students/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_student = Flight::student_service()->update($id, $data);
        
        if (!$updated_student) {
            Flight::json(['error' => 'Student not found'], 404);
        } else {
            Flight::json($updated_student, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Delete(
 * path="/students/{id}",
 * tags={"Students"},
 * summary="Deactivate a student (soft delete)",
 * description="Sets the student's status to 'inactive'.",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Student deactivated successfully"),
 * @OA\Response(response=404, description="Student not found")
 * )
 */
Flight::route('DELETE /students/@id', function ($id) {
    try {
        Flight::student_service()->delete($id);
        Flight::json(['message' => 'Student deactivated successfully'], 200); // <-- This is fixed
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

// --- Custom Routes ---

/**
 * @OA\Get(
 * path="/students/email/{email}",
 * tags={"Students"},
 * summary="Get a student by their email",
 * @OA\Parameter(name="email", in="path", required=true, @OA\Schema(type="string")),
 * @OA\Response(response=200, description="Student data"),
 * @OA\Response(response=404, description="Student not found")
 * )
 */
Flight::route('GET /students/email/@email', function ($email) {
    try {
        $student = Flight::student_service()->getStudentByEmail($email);
        if (!$student) {
            Flight::json(['error' => 'Student not found'], 404);
        } else {
            Flight::json($student, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/students/department/{id}",
 * tags={"Students"},
 * summary="Get a student by department ID (returns one)",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="Student data")
 * )
 */
Flight::route('GET /students/department/@id', function ($id) {
    try {
        $student = Flight::student_service()->getStudentByDepartment($id);
        Flight::json($student, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});