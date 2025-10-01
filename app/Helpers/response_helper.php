<?php

if (!function_exists('responseSuccess')) {
    function responseSuccess($message, $data = [], $status = 200)
    {
        return \Config\Services::response()
            ->setJSON([
                'status'   => $status,
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
                'status'   => $status,
                'error'    => $errors,
                'messages' => [
                    'error' => $message
                ],
                'data'     => null
            ])
            ->setStatusCode($status);
    }
}

if (!function_exists('responseInternalServerError')) {
    function responseInternalServerError($throwable = null, $message = 'Internal server error')
    {
        $errorDetail = (env('CI_ENVIRONMENT') === 'development' && $throwable)
            ? $throwable->getMessage()
            : null;

        return \Config\Services::response()
            ->setJSON([
                'status'   => 500,
                'error'    => $errorDetail,
                'messages' => [
                    'error' => $message
                ],
                'data'     => null
            ])
            ->setStatusCode(500);
    }
}
