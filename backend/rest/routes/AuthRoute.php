<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function () {

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login using email and password",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(property="email", type="string", example="demo@gmail.com"),
     *              @OA\Property(property="password", type="string", example="some_password")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Login result")
     * )
     */
    Flight::route('POST /login', function () {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::auth_service()->login($data);

            Flight::json($response);

        } catch (Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    });


    /**
     * @OA\Post(
     *      path="/auth/set-password",
     *      tags={"auth"},
     *      summary="Set password on first login",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_id","password"},
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="password", type="string", example="StrongPassword123")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Password set and JWT issued")
     * )
     */
    Flight::route('POST /set-password', function () {
        try {
            $data = Flight::request()->data->getData();
            $response = Flight::auth_service()->set_password($data);

            Flight::json($response);

        } catch (Exception $e) {
            Flight::halt(403, $e->getMessage());
        }
    });


    /**
     * @OA\Get(
     *     path="/auth/me",
     *     tags={"auth"},
     *     summary="Get current authenticated user",
     *     security={{"ApiKey": {}}},
     *     @OA\Response(response=200, description="Authenticated user")
     * )
     */
    Flight::route('GET /me', function () {
        try {
            $user = Flight::auth_service()->get_current_user();
            Flight::json(['data' => $user]);

        } catch (Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    });

});
