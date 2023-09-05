<?php

namespace App\Repositories;

use App\Http\Requests\Order\ListOrderRequest;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\Repository\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAllOrders(ListOrderRequest $request): array
    {
        // default orders by last 30 days
        $orders = Order::when($request->date_range, function (Builder $query) use ($request) {
            $query->whereBetween('created_at', [Carbon::parse($request->date_range['start_date']), Carbon::parse($request->date_range['end_date'])]);
        }, function (Builder $query) {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        });

        return [
            'body' => [
                'success' => 1,
                'data' => $orders->paginate($request->limit ?? 10)
            ],
            'code' => Response::HTTP_OK,
        ];
    }

    public function getOrderDataByUserId(ListOrderRequest $request, int $id): array
    {
        $orders = Order::where('user_id', $id);
        return [
            'body' => [
                'success' => 1,
                'data' => $orders->paginate($request->limit ?? 10)
            ],
            'code' => Response::HTTP_OK,
        ];

    }
}
