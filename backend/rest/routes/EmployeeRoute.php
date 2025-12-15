<?php

/**
 * @OA\Get(
 * path="/employees",
 * tags={"Employees"},
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
        $search = Flight::request()->query['search'] ?? '';
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 10;
        $order_column = Flight::request()->query['order_column'] ?? 'id';
        $order_direction = Flight::request()->query['order_direction'] ?? 'ASC';

        $result = Flight::employee_service()->getEmployeesPaginated(
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
 * path="/employees/{id}",
 * tags={"Employees"},
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
        $employee = Flight::employee_service()->getById($id);
        if (!$employee) {
            Flight::json(['error' => 'Employee not found'], 404);
        } else {
            Flight::json($employee, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Post(
 * path="/employees",
 * tags={"Employees"},
 * summary="Add a new employee",
 * @OA\RequestBody(
 * required=true,
 * description="Employee data",
 * @OA\JsonContent(
 * type="object",
 * required={"first_name", "last_name", "email", "password", "role"},
 * @OA\Property(property="first_name", type="string", example="John"),
 * @OA\Property(property="last_name", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", example="john.doe@example.com"),
 * @OA\Property(property="password", type="string", example="strongpassword123"),
 * @OA\Property(property="role", type="string", enum={"admin", "professor", "assistant"}, example="professor"),
 * @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 * @OA\Property(property="faculty_id", type="integer", example=1),
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
        $new_employee = Flight::employee_service()->add($data);
        Flight::json($new_employee, 201);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Put(
 * path="/employees/{id}",
 * tags={"Employees"},
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
 * @OA\Property(property="last_name", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", example="jane.doe@example.com"),
 * @OA\Property(property="role", type="string", enum={"admin", "professor", "assistant"}),
 * @OA\Property(property="status", type="string", enum={"active", "inactive"}),
 * @OA\Property(property="faculty_id", type="integer"),
 * @OA\Property(property="department_id", type="integer")
 * )
 * ),
 * @OA\Response(response=200, description="Employee updated successfully"),
 * @OA\Response(response=404, description="Employee not found")
 * )
 */
Flight::route('PUT /employees/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $updated_employee = Flight::employee_service()->update($id, $data);
        
        if (!$updated_employee) {
            Flight::json(['error' => 'Employee not found'], 404);
        } else {
            Flight::json($updated_employee, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});


/**
 * @OA\Delete(
 * path="/employees/{id}",
 * tags={"Employees"},
 * summary="Delete an employee",
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
        Flight::employee_service()->delete($id);
        Flight::json(['message' => 'Employee deleted successfully'], 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/employees/email/{email}",
 * tags={"Employees"},
 * summary="Get an employee by their email",
 * @OA\Parameter(name="email", in="path", required=true, @OA\Schema(type="string")),
 * @OA\Response(response=200, description="Employee data"),
 * @OA\Response(response=404, description="Employee not found")
 * )
 */
Flight::route('GET /employees/email/@email', function ($email) {
    try {
        $employee = Flight::employee_service()->getEmployeeByEmail($email);
        if (!$employee) {
            Flight::json(['error' => 'Employee not found'], 404);
        } else {
            Flight::json($employee, 200);
        }
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/employees/department/{id}",
 * tags={"Employees"},
 * summary="Get employees by department ID",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="A list of employees in the department")
 * )
 */
Flight::route('GET /employees/department/@id', function ($id) {
    try {
        $employees = Flight::employee_service()->getEmployeesByDepartment($id);
        Flight::json($employees, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/employees/faculty/{id}",
 * tags={"Employees"},
 * summary="Get employees by faculty ID",
 * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="A list of employees in the faculty")
 * )
 */
Flight::route('GET /employees/faculty/@id', function ($id) {
    try {
        $employees = Flight::employee_service()->getEmployeesByFaculty($id);
        Flight::json($employees, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/employees/role/{role}",
 * tags={"Employees"},
 * summary="Get employees by role",
 * @OA\Parameter(name="role", in="path", required=true, @OA\Schema(type="string", enum={"admin", "professor", "assistant"})),
 * @OA\Response(response=200, description="A list of employees matching the role")
 * )
 */
Flight::route('GET /employees/role/@role', function ($role) {
    try {
        $employees = Flight::employee_service()->getEmployeesByRole($role);
        Flight::json($employees, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Get(
 * path="/employees/status/{status}",
 * tags={"Employees"},
 * summary="Get employees by status",
 * @OA\Parameter(name="status", in="path", required=true, @OA\Schema(type="string", enum={"active", "inactive"})),
 * @OA\Response(response=200, description="A list of employees matching the status")
 * )
 */
Flight::route('GET /employees/status/@status', function ($status) {
    try {
        $employees = Flight::employee_service()->getEmployeesByStatus($status);
        Flight::json($employees, 200);
    } catch (Exception $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) $code = 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});