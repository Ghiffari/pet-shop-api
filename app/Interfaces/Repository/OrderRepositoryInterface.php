<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;

interface OrderRepositoryInterface
{
    public function getAllOrders(ListOrderRequest $request): array;

    public function getOrderDataByUserId(ListOrderRequest $request, int $id): array;

    public function getOrderDataByUuid(string $uuid): ?Order;

    public function createOrder(array $data): Order;

    public function updateOrder(UpdateOrderRequest $request, Order $order): Order;
}
