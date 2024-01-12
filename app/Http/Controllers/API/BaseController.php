<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = NULL, $code = 403)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        $messages = [];
        if (!empty($errorMessages)) {
            foreach ($errorMessages->toArray() as $key => $value) {
                $messages[$key] = $value[0];
            }
        }

        $response['errors'] = $messages;

        return response()->json($response, $code);
    }


    public function error($error, $code = 403)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        $response['errors'] = $error;

        return response()->json($response, $code);
    }
}
