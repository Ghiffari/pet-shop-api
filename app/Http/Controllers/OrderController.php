<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

    public function create(CreateOrderRequest $request, OrderService $service)
    {
        try {
            return $this->apiResponse(1, $service->createOrderData($request));
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'data' => [
                    'message' => $th->__toString()
                ]
            ], $th->getCode() > 0 ? $th->getCode() : Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateOrderRequest $request, string $uuid, OrderService $service)
    {
        try {
            return $this->apiResponse(1, $service->updateOrderData($request, $this->orderRepository->getOrderDataByUuid($uuid)));
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'data' => [
                    'message' => $th->__toString()
                ]
            ], $th->getCode() > 0 ? $th->getCode() : Response::HTTP_BAD_REQUEST);
        }
    }
}
