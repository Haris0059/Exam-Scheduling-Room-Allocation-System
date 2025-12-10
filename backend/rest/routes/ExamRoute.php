<?php

/**
 * @OA\Get(
 * path="/exams",
 * tags={"exams"},
 * summary="Get all exams",
 * @OA\Response(response=200, description="List of all exams")
 * )
 */
Flight::route('GET /exams', function () {
    try {
        $exams = Flight::exam_service()->getAll();
        Flight::json($exams, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/exams/{id}",
 * tags={"exams"},
 * summary="Get a single exam by its ID",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Exam data"),
 * @OA\Response(response=404, description="Exam not found")
 * )
 */
Flight::route('GET /exams/@id', function ($id) {
    try {
        $exam = Flight::exam_service()->getById($id);
        if (!$exam) {
            Flight::json(['error' => 'Exam not found'], 404);
        } else {
            Flight::json($exam, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Post(
 * path="/exams",
 * tags={"exams"},
 * summary="Schedule a new exam",
 * @OA\RequestBody(
 * required=true,
 * description="Exam data",
 * @OA\JsonContent(
 * type="object",
 * required={"course_id", "date", "start", "end", "type"},
 * @OA\Property(property="course_id", type="integer", example=101),
 * @OA\Property(property="date", type="string", format="date", example="2025-12-20"),
 * @OA\Property(property="start", type="string", format="time", example="10:00:00"),
 * @OA\Property(property="end", type="string", format="time", example="12:00:00"),
 * @OA\Property(property="type", type="string", enum={"midterm", "final", "makeup_midterm", "makeup_final"}, example="final")
 * )
 * ),
 * @OA\Response(response=201, description="Exam created successfully"),
 * @OA\Response(response=400, description="Invalid data (e.g., in the past, end time before start)")
 * )
 */
Flight::route('POST /exams', function () {
    try {
        $data = Flight::request()->data->getData();
        $new_exam = Flight::exam_service()->addExam($data);
        Flight::json($new_exam, 201);
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Put(
 * path="/exams/{id}",
 * tags={"exams"},
 * summary="Update an existing exam",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\RequestBody(
 * required=true,
 * description="Exam data to update",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="date", type="string", format="date", example="2025-12-21"),
 * @OA\Property(property="start", type="string", format="time", example="14:00:00"),
 * @OA\Property(property="end", type="string", format="time", example="16:00:00"),
 * @OA\Property(property="type", type="string", enum={"midterm", "final", "makeup_midterm", "makeup_final"})
 * )
 * ),
 * @OA\Response(response=200, description="Exam updated successfully"),
 * @OA\Response(response=400, description="Invalid data"),
 * @OA\Response(response=404, description="Exam not found")
 * )
 */
Flight::route('PUT /exams/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_exam = Flight::exam_service()->updateExam($id, $data);
        
        if (!$updated_exam) {
            Flight::json(['error' => 'Exam not found'], 404);
        } else {
            Flight::json($updated_exam, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Delete(
 * path="/exams/{id}",
 * tags={"exams"},
 * summary="Delete an exam",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Exam deleted successfully"),
 * @OA\Response(response=404, description="Exam not found")
 * )
 */
Flight::route('DELETE /exams/@id', function ($id) {
    try {
        Flight::exam_service()->delete($id);
        Flight::json(['message' => 'Exam deleted successfully'], 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

// --- Custom Routes ---

/**
 * @OA\Get(
 * path="/exams/date/{date}",
 * tags={"exams"},
 * summary="Get an exam by date (Note: DAO returns one)",
 * @OA\Parameter(name="date", in="path", required=true, @OA\Schema(type="string", format="date")),
 * @OA\Response(response=200, description="Exam data"),
 * @OA\Response(response=404, description="Exam not found")
 * )
 */
Flight::route('GET /exams/date/@date', function ($date) {
    try {
        $exam = Flight::exam_service()->getByDate($date);
        if (!$exam) {
            Flight::json(['error' => 'Exam not found'], 404);
        } else {
            Flight::json($exam, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/exams/type/{type}",
 * tags={"exams"},
 * summary="Get an exam by type (Note: DAO returns one)",
 * @OA\Parameter(name="type", in="path", required=true, @OA\Schema(type="string", enum={"midterm", "final", "makeup_midterm", "makeup_final"})),
 * @OA\Response(response=200, description="Exam data"),
 * @OA\Response(response=404, description="Exam not found")
 * )
 */
Flight::route('GET /exams/type/@type', function ($type) {
    try {
        $exam = Flight::exam_service()->getByType($type);
        if (!$exam) {
            Flight::json(['error' => 'Exam not found'], 404);
        } else {
            Flight::json($exam, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if (!is_int($code) || $code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});