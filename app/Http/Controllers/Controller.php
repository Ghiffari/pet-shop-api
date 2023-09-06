<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function apiResponse(bool $status, mixed $data, $code = 200)
    {
        return response()->json([
            'success' => $status ? 1 : 0,
            'data' => $data,
        ], $this->validateHttpCode($code) ? $code : Response::HTTP_BAD_REQUEST);
    }

    private function validateHttpCode(int $code): bool
    {
        return array_key_exists($code, Response::$statusTexts);
    }
}
