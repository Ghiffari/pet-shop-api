<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\ListOrderRequest;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function index(ListOrderRequest $request)
    {
        $res = $this->orderRepository->getAllOrders($request);
        return response()->json($res['body'], $res['code']);
    }
}
