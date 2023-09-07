<?php

namespace App\Interfaces\Service;

use App\Models\Order;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;

interface OrderServiceInterface
{
    public function createOrderData(CreateOrderRequest $request): array;

    public function updateOrderData(UpdateOrderRequest $request, Order $order): array;
}
