<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function login(LoginRequest $request)
    {
        $res = $this->userRepository->login($request);
        return response()->json($res['body'], $res['code']);
    }

    public function show()
    {
        return response()->json([
            'body' => [
                'success' => 1,
                'data' => Auth::guard('api')->user()
            ],
            'code' => Response::HTTP_OK,
        ]);
    }
}
