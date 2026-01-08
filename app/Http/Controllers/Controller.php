<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
abstract class Controller
{
    /**
     * Return a JSON response with given parameters and status code.
     *
     * @param array<string, mixed> $parameters
     * @param int $statusCode
     * @return JsonResponse
     */
    public function returnResponse(array $parameters, int $statusCode): JsonResponse
    {
        return response()->json($parameters, $statusCode);
    }

    /**
     * Return a successful JSON response.
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function responseSuccess(?string $message): JsonResponse 
    {
        return $this->returnResponse([
            'result'    => true,
            'message'   => is_null($message) ? 'N/A' : $message
        ], 200);
    }

    /**
     * Return a successful JSON response with additional data.
     *
     * @param array<string, mixed> $additionalData
     * @return JsonResponse
     */
    public function responseSuccessWithData(array $additionalData = []): JsonResponse 
    {
        return $this->returnResponse(array_merge([
            'result'    => true
        ], $additionalData), 200);
    }

    /**
     * Return a not found JSON response.
     *
     * @param string $message
     * @param array<string, mixed> $additionalData
     * @return JsonResponse
     */
    public function responseNotFound(string $message, array $additionalData = []): JsonResponse 
    {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 404);
    }

    /**
     * Return a failed JSON response.
     *
     * @param string $message
     * @param array<string, mixed> $additionalData
     * @return JsonResponse
     */
    public function responseFail(string $message, array $additionalData = []): JsonResponse 
    {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 200);
    }

    /**
     * Return an internal server error JSON response.
     *
     * @param string $message
     * @param array<string, mixed> $additionalData
     * @param int $statusCode
     * @return JsonResponse
     */
    public function responseInternalServerError(string $message, array $additionalData = [], int $statusCode = 501): JsonResponse
    {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), $statusCode);
    }

    /**
     * Return an unauthorized JSON response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function responseUnauthorized(string $message): JsonResponse 
    {
        return $this->returnResponse([
            'result'    => false,
            'message'   => $message
        ], 401);
    }
}
