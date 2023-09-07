<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\Admin\ListUserRequest;

class AdminController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->userRepository->login($request, true));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function logout(Request $request)
    {
        return $this->apiResponse(1, $this->userRepository->logout($request->bearerToken()));
    }

    public function userListing(ListUserRequest $request)
    {
        return $this->apiResponse(1, $this->userRepository->getAllUsers($request));
    }
}
