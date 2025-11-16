 * @OA\Parameter(name="limit", in="query", description="Pagination limit", @OA\Schema(type="integer")),
<?php

/**
 * @OA\Get(
 * path="/courses",
 * tags={"courses"},
 * summary="Get all courses with pagination",
 * @OA\Parameter(
 * name="search",
 * in="query",
 * description="Search term for course name or code",
 * @OA\Schema(type="string")
 * ),
 * @OA\Parameter(name="offset", in="query", description="Pagination offset", @OA\Schema(type="integer")),
 * @OA\Parameter(name="limit", in="query", description="Pagination limit", @OA\Schema(type="integer")),
 * @OA\Parameter(name="order_column", in="query", description="Column to order by", @OA\Schema(type="string", default="id")),
 * @OA\Parameter(name="order_direction", in="query", description="ASC or DESC", @OA\Schema(type="string", default="ASC")),
 * @OA\Response(
 * response=200,
 * description="List of courses with total count"
 * )
 * )
 */
Flight::route('GET /courses', function () {
    try {
        // Get query parameters with defaults
        $search = Flight::request()->query['search'] ?? '';
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 10;
        $order_column = Flight::request()->query['order_column'] ?? 'id';
        $order_direction = Flight::request()->query['order_direction'] ?? 'ASC';

        // Call the new service method
        $result = Flight::courseService()->getCoursesPaginated(
            $offset,
            $limit,
            $search,
            $order_column,
            $order_direction
        );

        Flight::json($result, 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});


/**
 * @OA\Get(
 * path="/courses/{id}",
 * tags={"courses"},
 * summary="Get a single course by its ID",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the course",
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Course data"),
 * @OA\Response(response=404, description="Course not found")
 * )
 */
Flight::route('GET /courses/@id', function ($id) {
    try {
        $course = Flight::courseService()->get_by_id($id);
        if (!$course) {
            Flight::json(['error' => 'Course not found'], 404);
        } else {
            Flight::json($course, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Post(
 * path="/courses",
 * tags={"courses"},
 * summary="Add a new course",
 * @OA\RequestBody(
 * required=true,
 * description="Course data",
 * @OA\JsonContent(
 * type="object",
 * required={"name", "code", "faculty_id", "department_id"},
 * @OA\Property(property="name", type="string", example="Web Programming"),
 * @OA\Property(property="code", type="string", example="CS308"),
 * @OA\Property(property="faculty_id", type="integer", example=1),
 * @OA\Property(property="department_id", type="integer", example=1)
 * )
 * ),
 * @OA\Response(response=201, description="Course created successfully"),
 * @OA\Response(response=400, description="Invalid data")
 * )
 */
Flight::route('POST /courses', function () {
    try {
        $data = Flight::request()->data->getData();
        
        // You can add validation here from your CourseService if you have it
        // e.g., Flight::courseService()->validateCourseData($data);

        $new_course = Flight::courseService()->create($data);
        Flight::json($new_course, 201);
    } catch (Exception $e) {
        Flight::json(['errors' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Put(
 * path="/courses/{id}",
 * tags={"courses"},
 * summary="Update an existing course",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\RequestBody(
 * required=true,
 * description="Course data to update",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="name", type="string", example="Advanced Web Programming"),
 * @OA\Property(property="code", type="string", example="CS408")
 * )
 * ),
 * @OA\Response(response=200, description="Course updated successfully"),
 * @OA\Response(response=404, description="Course not found")
 * )
 */
Flight::route('PUT /courses/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_course = Flight::courseService()->update($id, $data);
        
        if (!$updated_course) {
            Flight::json(['error' => 'Course not found'], 404);
        } else {
            Flight::json($updated_course, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Delete(
 * path="/courses/{id}",
 * tags={"courses"},
 * summary="Delete a course",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Course deleted successfully"),
 * @OA\Response(response=404, description="Course not found")
 * )
 */
Flight::route('DELETE /courses/@id', function ($id) {
    try {
        Flight::courseService()->delete($id);
        Flight::json(['message' => 'Course deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


// --- Custom Routes ---

/**
 * @OA\Get(
 * path="/courses/code/{code}",
 * tags={"courses"},
 * summary="Get a course by its code",
 * @OA\Parameter(name="code", in="path", required=true, @OA\Schema(type="string")),
 * @OA\Response(response=200, description="Course data"),
 * @OA\Response(response=404, description="Course not found")
 * )
 */
Flight::route('GET /courses/code/@code', function ($code) {
    try {
        $course = Flight::courseService()->getCourseByCode($code);
        if (!$course) {
            Flight::json(['error' => 'Course not found'], 404);
        } else {
            Flight::json($course, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});


/**
 * @OA\Get(
 * path="/courses/department/{id}",
 * tags={"courses"},
 * summary="Get courses by department ID",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="List of courses")
 * )
 */
Flight::route('GET /courses/department/@id', function ($id) {
    try {
        $courses = Flight::courseService()->getCoursesByDepartment($id);
        Flight::json($courses, 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});