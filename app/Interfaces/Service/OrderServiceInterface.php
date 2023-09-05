<?php

namespace App\Interfaces\Service;

use App\Http\Requests\Order\CreateOrderRequest;

interface OrderServiceInterface
{
    public function createOrderData(CreateOrderRequest $request): array;
}
