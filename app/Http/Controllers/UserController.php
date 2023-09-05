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

    public function orders(ListOrderRequest $request)
    {
        $res = $this->orderRepository->getOrderDataByUserId($request, Auth::id());
        return response()->json($res['body'], $res['code']);
    }
}
