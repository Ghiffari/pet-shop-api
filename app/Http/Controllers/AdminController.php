<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {

    }

    public function login(LoginRequest $request)
    {
        $res = $this->userRepository->login($request, true);
        return response()->json($res['body'], $res['code']);
    }

    public function userIndex(Request $request)
    {
        $res = $this->userRepository->getAllUsers($request);
        return response()->json($res['body'], $res['code']);
    }
}
