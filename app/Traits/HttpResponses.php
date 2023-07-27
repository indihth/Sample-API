<?php

namespace App\Traits;

trait HttpResponses {

    // Success Message -takes the data sent back to the user, the message and the status code (defaulted to 200)
    protected function success($data, $message = null, $code = 200) 
    {
        // JSON() accepts an array and converts to json object
        return response()->json([
            'status' => 'Request was successful',
            'message' => $message,
            'data' => $data
        // outside of [] is the default code being sent
        ], $code);
    }

    // Error message 
    protected function error($data,  $message = null, $code) 
    {
        // JSON() accepts an array and converts to json object
        return response()->json([
            'status' => 'Error has occurred',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}