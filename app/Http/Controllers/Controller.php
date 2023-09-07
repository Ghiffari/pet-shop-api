<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function apiResponse(int $status, mixed $data, $code = 200): JsonResponse
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
