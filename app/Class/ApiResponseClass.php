<?php

namespace App\Class;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    public static function sendResponse($result , $message ,$code=200){
        $response=[
            'success' => true,
            'data'    => $result
        ];
        if(!empty($message)){
            $response['message'] =$message;
        }
        return response()->json($response, $code);
    }

    // public static function error(string $message, array $errors = [], int $status = 400)
    // {
    //     return response()->json([
    //         'success' => false,
    //         'message' => $message,
    //         'errors' => $errors,
    //     ], $status);
    // }
}
