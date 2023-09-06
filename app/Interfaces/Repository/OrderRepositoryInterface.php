<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\Order\ListOrderRequest;
use App\Models\Order;

interface OrderRepositoryInterface
{
    public function getAllOrders(ListOrderRequest $request): array;

    public function getOrderDataByUserId(ListOrderRequest $request, int $id): array;

    public function getOrderDataByUuid(string $uuid): ?Order;

    public function createOrder(array $data): Order;

    public function updateOrder(array $data, Order $order): Order;
}
