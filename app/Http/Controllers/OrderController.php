<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function index(ListOrderRequest $request): JsonResponse
    {
        try {
            return $this->apiResponse(1, $this->orderRepository->getAllOrders($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function create(CreateOrderRequest $request, OrderService $service): JsonResponse
    {
        try {
            DB::beginTransaction();
            $order = $service->createOrderData($request);
            DB::commit();
            return $this->apiResponse(1, $order);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function update(UpdateOrderRequest $request, string $uuid, OrderService $service): JsonResponse
    {
        try {
            DB::beginTransaction();
            $order = $service->updateOrderData($request, $this->orderRepository->getOrderDataByUuid($uuid));
            DB::commit();
            return $this->apiResponse(1, $order);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
