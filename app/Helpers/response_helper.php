<?php

if (!function_exists('responseSuccess')) {
    function responseSuccess($message, $data = [], $status = 200)
    {
        return \Config\Services::response()
            ->setJSON([
                'statusCode'   => $status,
                'status'    => 'success',
                'error'    => null,
                'messages' => [
                    'success' => $message
                ],
                'data'     => $data
            ])
            ->setStatusCode($status);
    }
}

if (!function_exists('responseError')) {
    function responseError($message, $status = 400, $errors = null)
    {
        return \Config\Services::response()
            ->setJSON([
                'statusCode'   => $status,
                'status'    => 'error',
                'error'    => $errors,
                'message' => $message,
                'data'     => null
            ])
            ->setStatusCode($status);
    }
}

if (!function_exists('responseInternalServerError')) {
    function responseInternalServerError($error)
    {
        $message = $error instanceof \Throwable ? $error->getMessage() : (string) $error;

        return \Config\Services::response()
            ->setJSON([
                'title' => 'Error',
                'type' => 'Error',
                'code' => 500,
                'message' => $message
            ])
            ->setStatusCode(500);
    }
}
