<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Constants\OrderConstant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderStatusRepository;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Interfaces\Service\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{

    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private OrderStatusRepository $orderStatusRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository, OrderStatusRepository $orderStatusRepository, PaymentRepository $paymentRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function createOrderData(CreateOrderRequest $request): array
    {
        try {
            DB::beginTransaction();
            $amount = $this->calculateOrderAmount($request->products);
            $status = $this->orderStatusRepository->getOrderStatusByTitle(OrderConstant::STATUS_PENDING_PAYMENT);
            $payment = $this->paymentRepository->createPayment($request->all());
            $data = [
                'user_id' => Auth::id(),
                'order_status_id' => $status->id,
                'payment_id' => $payment->id,
                'uuid' => Str::uuid(),
                'products' => $request->products,
                'address' => $request->address,
                'amount' => $amount,
                'delivery_fee' => $amount > 500 ? 15 : 0
            ];
            $order = $this->orderRepository->createOrder($data);
            DB::commit();
            return [
                'body' => [
                    'success' => 1,
                    'data' => [
                        'order' => $order,
                        'products' => $this->getStripeProductData($request->products)
                    ]
                ],
                'code' => Response::HTTP_OK,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    private function calculateOrderAmount(array $products): float
    {
        $amount = floatval(0);

        foreach($products as $product){
            $res = $this->productRepository->getProductByUuid($product['product']);
            if($res){
                $amount += ($res->price * $product['quantity']);
            }
        }

        return $amount;
    }

    private function getStripeProductData(array $products): array
    {
        $stripeProducts = [];
        foreach ($products as $product) {
            $res = $this->productRepository->getProductByUuid($product['product']);
            $stripeProducts[] = [
                'title' => $res->title,
                'price' => $res->price,
                'quantity' => $product['quantity'],
            ];
        }
        return $stripeProducts;
    }

}
