<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
abstract class Controller
{
    protected function error($message = 'Terdapat validasi yang salah, Harap cek kembali!', $errors = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $response = [
            'success'   => false,
            'message'   => $message
        ];
        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }
        return response()->json($response, $code);
    }

    protected function success($data = null, $message = 'Success!', int $code = Response::HTTP_OK)
    {
        $response = [
            'success'   => true,
            'message'   => $message
        ];

        if (!is_null($data)) {
            $response['metadata'] = $data;
        }

        return response()->json($response, $code);
    }
}
