<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Status Code 200
     * Send Token
     */
    public function sendToken($token, $expiredIn) {
        return response()->json([ 'access_token' => $token, 'token_type' => 'bearer', 'expires_in' => $expiredIn], 200); 
    }

    /**
     * Status Code 200
     * Send Paginate
     */
    public function sendPaginate($paginate) {
        $message = 'Data available.';
        if ($paginate->count() == 0) {
            $message = 'Data empty!';
        }
        return response()->json([
            'data' => $paginate->items(),
            'info' => [
                'count' => $paginate->count(),
                'total' => $paginate->total(),
                'per_page' => $paginate->perPage(),
                'current_page' => $paginate->currentPage()
            ],
            'status' => true,
            'message' => $message
        ], 200); 
    }

    /**
     * Status code 200
     * Success
     */
    public function sendSuccess($data, $message = '') {
        return response()->json(['message' => $message, 'data' => $data, 'status' => true], 200);
    }

    /**
     * Status code 201
     * Created
     */
    public function sendCreated($data, $message = 'Successfully created') {
        return response()->json(['message' => $message, 'data' => $data, 'status' => true], 201);
    }

    /**
     * Status Code 400
     * Bad Request
     */
    public function sendBadRequest($message = 'Bad Request!') {
        return response()->json(['message' => $message, 'status' => false], 400);
    }

    /**
     * Status Code 401
     * Unauthorized
     */
    public function sendUnauthorized($message = 'Unauthorized!') {
        return response()->json(['message' => $message, 'status' => false], 401);
    }

    /**
     * Status Code 403
     * Forbidden
     */
    public function sendForbidden($message = 'Forbidden!') {
        return response()->json(['message' => $message, 'status' => false], 403);
    }

    /**
     * Status Code 404
     * Not found
     */
    public function sendNotFound($message = 'Not found!') {
        return response()->json(['message' => $message, 'status' => false], 404);
    }

    /**
     * Status code 422
     * Unprocessed Entity
     */
    public function sendUnprocessedEntity($errors, $message = 'Validation errors') {
        return response()->json(['message' => $message, 'errors' =>  $errors, 'status' => false], 422);
    }
}
