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
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Interfaces\Service\OrderServiceInterface;
use App\Util\OrderHelper;
use Ghiffariaq\Stripe\Services\StripeService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService implements OrderServiceInterface
{

    use OrderHelper;

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
            $status = $this->orderStatusRepository->getOrderStatusByTitle(OrderConstant::STATUS_OPEN);
            $data = [
                'user_id' => Auth::id(),
                'order_status_id' => $status->id,
                'uuid' => Str::uuid(),
                'products' => $request->products,
                'address' => $request->address,
                'amount' => $amount,
                'delivery_fee' => $amount > 500 ? 15 : 0
            ];
            $order = $this->orderRepository->createOrder($data);
            DB::commit();

            $response = [
                'order' => $order
            ];

            return $response;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function updateOrderData(UpdateOrderRequest $request, Order $order): array
    {
        try {
            DB::beginTransaction();
            $updatedAttribute = [];
            if($request->order_status_uuid){
                $status = $this->orderStatusRepository->getOrderStatusByUuid($request->order_status_uuid);
                if(!Auth::user()->is_admin && !in_array($status, OrderConstant::LIST_OF_USER_PAYMENT_UPDATE)){
                    throw new BadRequestHttpException("User only able to update " . OrderConstant::STATUS_OPEN . " and " . OrderConstant::STATUS_PENDING_PAYMENT);
                }
                $updatedAttribute['order_status_id'] = $status->id;
            }

            if($request->payment_uuid){
                $payment = $this->paymentRepository->getPaymentByUuid($request->payment_uuid);
                $updatedAttribute['payment_id'] = $payment->id;
                $status = $this->orderStatusRepository->getOrderStatusByTitle(OrderConstant::STATUS_PENDING_PAYMENT);
                $updatedAttribute['order_status_id'] = $status->id;
                if ($payment->type === OrderConstant::PAYMENT_STRIPE) {
                    $stripeService = new StripeService();
                    $checkout = $stripeService->generateCheckoutData([
                        'order' => $order,
                        'products' => $this->getStripeProductData($order->products)
                    ]);
                    $response['url'] = $checkout->url;
                }
            }

            $order = $this->orderRepository->updateOrder($updatedAttribute, $order);
            DB::commit();
            $response['order'] = $order;
            return $response;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    // public function calculateOrderAmount(array $products): float
    // {
    //     $amount = floatval(0);

    //     foreach($products as $product){
    //         $res = $this->productRepository->getProductByUuid($product['product']);
    //         if($res){
    //             $amount += ($res->price * $product['quantity']);
    //         }
    //     }

    //     return $amount;
    // }

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
