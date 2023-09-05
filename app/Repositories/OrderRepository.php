<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Interfaces\Repository\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{

    public function getAllOrders(ListOrderRequest $request): array
    {
        // default orders by last 30 days
        $orders = Order::when($request->get('date_range'), function (Builder $query) use ($request) {
            $query->whereBetween('created_at', [Carbon::parse($request->get('date_range')['start_date']), Carbon::parse($request->get('date_range')['end_date'])]);
        }, function (Builder $query) {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        });

        return [
            'body' => [
                'success' => 1,
                'data' => $orders->paginate($request->get('limit') ?? 10)
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
                'data' => $orders->paginate($request->get('limit') ?? 10)
            ],
            'code' => Response::HTTP_OK,
        ];
    }

    public function getOrderDataByUuid(string $uuid): ?Order
    {
        return Order::where('uuid', $uuid)->first();
    }

    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function updateOrder(UpdateOrderRequest $request, Order $order): Order
    {

        try {
            DB::beginTransaction();
            $order->update([
                'order_status_id' => $request->get('order_status_id') ?? $order->order_status_id,
            ]);
            DB::commit();
            return $order;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
