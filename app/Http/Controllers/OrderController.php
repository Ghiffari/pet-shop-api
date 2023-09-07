<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function index(ListOrderRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->orderRepository->getAllOrders($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function create(CreateOrderRequest $request, OrderService $service)
    {
        try {
            return $this->apiResponse(1, $service->createOrderData($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function update(UpdateOrderRequest $request, string $uuid, OrderService $service)
    {
        try {
            return $this->apiResponse(1, $service->updateOrderData($request, $this->orderRepository->getOrderDataByUuid($uuid)));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
