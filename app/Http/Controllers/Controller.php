<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function apiResponse(bool $status, mixed $data, $code = 200)
    {
        return response()->json([
            'success' => $status ? 1 : 0,
            'data' => $data,
        ], $code);
    }
}
