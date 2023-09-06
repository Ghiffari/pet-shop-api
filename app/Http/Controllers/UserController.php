<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ListOrderRequest;
use App\Repositories\UserRepository;
use App\Http\Requests\User\LoginRequest;
use App\Repositories\OrderRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->userRepository->login($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function show()
    {
        try {
            return $this->apiResponse(1, Auth::guard('api')->user());
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function orders(ListOrderRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->orderRepository->getOrderDataByUserId($request, Auth::id()));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
