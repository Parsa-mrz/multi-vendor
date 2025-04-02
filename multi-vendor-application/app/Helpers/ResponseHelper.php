<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseHelper
 * Provides standardized JSON response formatting for the application.
 */
class ResponseHelper
{
    /**
     * Generate a successful JSON response.
     *
     * @param  string  $message  The success message.
     * @param  mixed|null  $data  The data to include in the response. Default is null.
     * @param  int  $status  The HTTP status code, using Symfony's Response constants. Default is Response::HTTP_OK.
     * @return JsonResponse The formatted JSON response.
     */
    public static function success(string $message, $data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $status);
    }

    /**
     * Generate an error JSON response.
     *
     * @param  string  $message  The error message.
     * @param  mixed|null  $errors  Additional error details (e.g., validation errors). Default is null.
     * @param  int  $status  The HTTP status code, using Symfony's Response constants. Default is Response::HTTP_BAD_REQUEST.
     * @return JsonResponse The formatted JSON response.
     */
    public static function error(string $message, $errors = null, int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $status);
    }
}
