<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Ghiffariaq\Stripe\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{

    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function index(ListOrderRequest $request)
    {
        $res = $this->orderRepository->getAllOrders($request);
        return response()->json($res['body'], $res['code']);
    }

    public function create(CreateOrderRequest $request, OrderService $service)
    {
        try {
            $res = $service->createOrderData($request);
            $stripe = new StripeService();
            $url = $stripe->generateCheckoutData($res['body']['data'])->url;
            return $this->apiResponse(1, $url);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => 0,
                'data' => [
                    'message' => $th->__toString()
                ]
            ], $th->getCode() > 0 ? $th->getCode() : Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateOrderRequest $request, string $uuid)
    {
        try {
            $res = $this->orderRepository->updateOrder($request, $this->orderRepository->getOrderDataByUuid($uuid));
            return response()->json($res['body'], $res['code']);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'data' => [
                    'message' => $th->__toString()
                ]
            ], $th->getCode() > 0 ? $th->getCode() : Response::HTTP_BAD_REQUEST);
        }
    }
}
