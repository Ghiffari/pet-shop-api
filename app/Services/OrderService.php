<?php

namespace App\Services;

use Throwable;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Constants\OrderConstant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderStatusRepository;
use Ghiffariaq\Stripe\Services\StripeService;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Interfaces\Service\OrderServiceInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService implements OrderServiceInterface
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private OrderStatusRepository $orderStatusRepository;
    private PaymentRepository $paymentRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->productRepository = new ProductRepository();
        $this->orderStatusRepository = new OrderStatusRepository();
        $this->paymentRepository = new PaymentRepository();
    }

    /**
     * @param array<Order> $order
     * @throws Throwable
     */
    public function createOrderData(CreateOrderRequest $request): array
    {
        try {
            DB::beginTransaction();
            $amount = $this->calculateOrderAmount($request->get('products'));
            $status = $this->orderStatusRepository->getOrderStatusByTitle(OrderConstant::STATUS_OPEN);
            $data = [
                'user_id' => Auth::id(),
                'order_status_id' => $status->id,
                'uuid' => Str::uuid(),
                'products' => $request->get('products'),
                'address' => $request->get('address'),
                'amount' => $amount,
                'delivery_fee' => $amount > 500 ? 15 : 0,
            ];
            $order = $this->orderRepository->createOrder($data);
            DB::commit();
            return [
                'order' => $order,
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function updateOrderData(UpdateOrderRequest $request, Order $order): array
    {
        try {
            DB::beginTransaction();
            $updatedAttribute = [];
            if ($request->get('order_status_uuid')) {
                $status = $this->orderStatusRepository->getOrderStatusByUuid($request->get('order_status_uuid'));
                if (!Auth::user()->is_admin && !in_array($status, OrderConstant::LIST_OF_USER_STATUS_UPDATE)) {
                    throw new BadRequestHttpException("User only able to update " . OrderConstant::STATUS_OPEN . " and " . OrderConstant::STATUS_PENDING_PAYMENT);
                }
                $updatedAttribute['order_status_id'] = $status->id;
            }

            if ($request->get('payment_uuid')) {
                $payment = $this->paymentRepository->getPaymentByUuid($request->get('payment_uuid'));
                $this->orderRepository->updateOrder([
                    'payment_id' => $payment->id,
                ], $order);
                $status = $this->orderStatusRepository->getOrderStatusByTitle(OrderConstant::STATUS_PENDING_PAYMENT);
                $updatedAttribute['order_status_id'] = $status->id;
                if ($payment->type === OrderConstant::PAYMENT_STRIPE) {
                    $stripeService = new StripeService();
                    $checkout = $stripeService->generateCheckoutData([
                        'order' => $order,
                        'products' => $this->getStripeProductData($order->products),
                    ]);
                    $response['url'] = $checkout->url;
                }
            }

            $order = $this->orderRepository->updateOrder($updatedAttribute, $order);
            DB::commit();
            $response['order'] = $order;
            return $response;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    /**
     * @param array<array> $products
     */

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

    public function calculateOrderAmount(array $products): float
    {
        $amount = floatval(0);
        $productRepository = new ProductRepository();
        foreach ($products as $product) {
            $res = $productRepository->getProductByUuid($product['product']);
            $amount += $res->price * $product['quantity'];
        }

        return $amount;
    }
}
