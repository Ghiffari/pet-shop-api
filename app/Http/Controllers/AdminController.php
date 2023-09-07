<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ListUserRequest;
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
        try {
            return $this->apiResponse(1, $this->userRepository->login($request, true));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function userListing(ListUserRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->userRepository->getAllUsers($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
