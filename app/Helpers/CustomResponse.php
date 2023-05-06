<?php

namespace app\Helpers;
use Symfony\Component\HttpFoundation\Response;

class CustomResponse
{
    public static function response($data)
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ], Response::HTTP_OK);
    }
}