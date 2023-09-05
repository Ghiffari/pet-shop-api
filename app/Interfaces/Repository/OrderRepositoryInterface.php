<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\Order\ListOrderRequest;

interface OrderRepositoryInterface
{
    public function getAllOrders(ListOrderRequest $request): array;

    public function getOrderDataByUserId(ListOrderRequest $request, int $id): array;
}
