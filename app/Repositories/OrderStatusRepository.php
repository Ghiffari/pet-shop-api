<?php

namespace App\Repositories;

use App\Models\OrderStatus;
use App\Http\Requests\OrderStatus\ListOrderStatusRequest;
use App\Interfaces\Repository\OrderStatusRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class OrderStatusRepository implements OrderStatusRepositoryInterface
{
    public function getAllOrderStatuses(ListOrderStatusRequest $request): LengthAwarePaginator
    {
        $status = OrderStatus::when($request->get('sortBy'), function (Builder $query) use ($request): void {
            $query->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        });
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
