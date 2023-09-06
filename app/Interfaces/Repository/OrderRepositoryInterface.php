<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\Order\ListOrderRequest;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getAllOrders(ListOrderRequest $request): LengthAwarePaginator;

    public function getOrderDataByUserId(ListOrderRequest $request, int $id): array;

    public function getOrderDataByUuid(string $uuid): ?Order;

    public function createOrder(array $data): Order;

    public function updateOrder(array $data, Order $order): Order;
}
