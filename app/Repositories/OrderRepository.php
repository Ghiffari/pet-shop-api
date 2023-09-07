<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Order\ListOrderRequest;
use App\Interfaces\Repository\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAllOrders(ListOrderRequest $request): LengthAwarePaginator
    {
        // default orders by last 30 days
        $orders = Order::when($request->get('date_range'), function (Builder $query) use ($request): void {
            $query->whereBetween('created_at', [Carbon::parse($request->get('date_range')['start_date']), Carbon::parse($request->get('date_range')['end_date'])]);
        }, function (Builder $query): void {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        });

        if ($request->get('sortBy')) {
            $orders->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        }

        return $orders->paginate($request->get('limit') ?? 10);
    }

    public function getOrderDataByUserId(ListOrderRequest $request, int $id): LengthAwarePaginator
    {
        $orders = Order::where('user_id', $id)
            ->when($request->get('sortBy'), function (Builder $query) use ($request): void {
                $query->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
            });

        return $orders->paginate($request->get('limit') ?? 10);
    }

    public function getOrderDataByUuid(string $uuid): ?Order
    {
        return Order::where('uuid', $uuid)->first();
    }

    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function updateOrder(array $data, Order $order): Order
    {
        $order->update($data);
        return $order;
    }
}
