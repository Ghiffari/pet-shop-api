<?php

namespace App\Repositories;

use App\Http\Requests\OrderStatus\ListOrderStatusRequest;
use App\Interfaces\Repository\OrderStatusRepositoryInterface;
use App\Models\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderStatusRepository implements OrderStatusRepositoryInterface
{
    public function getAllOrderStatuses(ListOrderStatusRequest $request): LengthAwarePaginator
    {
        $status = OrderStatus::query();
        if ($request->sortBy) {
            $status->orderBy($request->sortBy, $request->desc ? "desc" : "asc");
        }
        return $status->paginate($request->get('limit') ?? 10);
    }

    public function getOrderStatusByTitle(string $title): ?OrderStatus
    {
        return OrderStatus::whereTitle($title)->first();
    }

    public function getOrderStatusByUuid(string $uuid): ?OrderStatus
    {
        return OrderStatus::whereUuid($uuid)->first();
    }

}
