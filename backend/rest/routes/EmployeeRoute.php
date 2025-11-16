<?php

/**
 * @OA\Get(
 * path="/employees",
 * tags={"employees"},
 * summary="Get all employees with pagination",
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
 * description="List of employees with total count"
 * )
 * )
 */
Flight::route('GET /employees', function () {
    try {
        // Get query parameters with defaults
        $search = Flight::request()->query['search'] ?? '';
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 10;
        $order_column = Flight::request()->query['order_column'] ?? 'id';
        $order_direction = Flight::request()->query['order_direction'] ?? 'ASC';

        $result = Flight::employeeService()->getEmployeesPaginated(
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
 * path="/employees/{id}",
 * tags={"employees"},
 * summary="Get a single employee by their ID",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the employee",
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Employee data"),
 * @OA\Response(response=404, description="Employee not found")
 * )
 */
Flight::route('GET /employees/@id', function ($id) {
    try {
        $employee = Flight::employeeService()->get_by_id($id);
        if (!$employee) {
            Flight::json(['error' => 'Employee not found'], 404);
        } else {
            Flight::json($employee, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Post(
 * path="/employees",
 * tags={"employees"},
 * summary="Add a new employee",
 * @OA\RequestBody(
 * required=true,
 * description="Employee data",
 * @OA\JsonContent(
 * type="object",
 * required={"first_name", "last_name", "email", "role"},
 * @OA\Property(property="first_name", type="string", example="John"),
 * @OA\Property(property="last_name", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", example="john.doe@example.com"),
 * @OA\Property(property="role", type="string", example="professor"),
 * @OA\Property(property="status", type="string", example="active"),
 * @OA\Property(property="department_id", type="integer", example=1)
 * )
 * ),
 * @OA\Response(response=201, description="Employee created successfully"),
 * @OA\Response(response=400, description="Invalid data")
 * )
 */
Flight::route('POST /employees', function () {
    try {
        $data = Flight::request()->data->getData();
        $new_employee = Flight::employeeService()->create($data);
        Flight::json($new_employee, 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Put(
 * path="/employees/{id}",
 * tags={"employees"},
 * summary="Update an existing employee",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\RequestBody(
 * required=true,
 * description="Employee data to update",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="first_name", type="string", example="Jane"),
 * @OA\Property(property="email", type="string", example="jane.doe@example.com")
 * )
 * ),
 * @OA\Response(response=200, description="Employee updated successfully"),
 * @OA\Response(response=404, description="Employee not found")
 * )
 */
Flight::route('PUT /employees/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_employee = Flight::employeeService()->update($id, $data);
        
        if (!$updated_employee) {
            Flight::json(['error' => 'Employee not found'], 404);
        } else {
            Flight::json($updated_employee, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Delete(
 * path="/employees/{id}",
 * tags={"employees"},
 * summary="Delete an employee (soft delete recommended)",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer")
 * ),
 * @OA\Response(response=200, description="Employee deleted successfully"),
 * @OA\Response(response=404, description="Employee not found")
 * )
 */
Flight::route('DELETE /employees/@id', function ($id) {
    try {
        Flight::employeeService()->delete($id);
        Flight::json(['message' => 'Employee deleted successfully'], 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});


/**
 * @OA\Get(
 * path="/employees/email/{email}",
 * tags={"employees"},
 * summary="Get an employee by their email",
 * @OA\Parameter(name="email", in="path", required=true, @OA\Schema(type="string")),
 * @OA\Response(response=200, description="Employee data"),
 * @OA\Response(response=404, description="Employee not found")
 * )
 */
Flight::route('GET /employees/email/@email', function ($email) {
    try {
        $employee = Flight::employeeService()->getEmployeeByEmail($email);
        if (!$employee) {
            Flight::json(['error' => 'Employee not found'], 404);
        } else {
            Flight::json($employee, 200);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 * path="/employees/department/{id}",
 * tags={"employees"},
 * summary="Get employees by department ID",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="List of employees")
 * )
 */
Flight::route('GET /employees/department/@id', function ($id) {
    try {
        // DAO uses query_unique, so this will only return one employee
        $employees = Flight::employeeService()->getEmployeesByDepartment($id);
        Flight::json($employees, 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 * path="/employees/faculty/{id}",
 * tags={"employees"},
 * summary="Get employees by faculty ID",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="List of employees")
 * )
 */
Flight::route('GET /employees/faculty/@id', function ($id) {
    try {
        // DAO uses query_unique, so this will only return one employee
        $employees = Flight::employeeService()->getEmployeesByFaculty($id);
        Flight::json($employees, 200);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});