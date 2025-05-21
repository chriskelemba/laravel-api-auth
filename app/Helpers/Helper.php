<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('sendApiResponse')) {
    /**
     * function sendApiResponse
     *
     * Format a successful API response.
     *
     * @author Christian Kelemba <kabogp@gmail.com>
     */
    function sendApiResponse($result, $message = '', $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }
}