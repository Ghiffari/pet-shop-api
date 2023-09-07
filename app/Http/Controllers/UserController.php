<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\Order\ListOrderRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return $this->apiResponse(1, $this->userRepository->login($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->apiResponse(1, $this->userRepository->logout($request->bearerToken()));
    }

    public function show(): JsonResponse
    {
        return $this->apiResponse(1, Auth::guard('api')->user());
    }

    public function orders(ListOrderRequest $request): JsonResponse
    {
        return $this->apiResponse(1, $this->orderRepository->getOrderDataByUserId($request, Auth::id()));
    }
}
