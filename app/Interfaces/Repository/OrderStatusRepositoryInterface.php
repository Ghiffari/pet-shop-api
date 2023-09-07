<?php

namespace App\Interfaces\Repository;

use App\Models\OrderStatus;
use App\Http\Requests\OrderStatus\ListOrderStatusRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderStatusRepositoryInterface
{
    public function getAllOrderStatuses(ListOrderStatusRequest $request): LengthAwarePaginator;

    public function getOrderStatusByTitle(string $title): ?OrderStatus;

    public function getOrderStatusByUuid(string $uuid): ?OrderStatus;
}
