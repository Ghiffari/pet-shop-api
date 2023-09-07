<?php

namespace Ghiffariaq\Stripe\Services;

use App\Constants\OrderConstant;
use Ghiffariaq\Stripe\Util\StripeClient;
use App\Repositories\OrderStatusRepository;
use Ghiffariaq\Stripe\Interfaces\StripeServiceInterface;

class StripeService implements StripeServiceInterface
{
    use StripeClient;

    public function generateCheckoutData($data): mixed
    {
        $checkout = $this->createCheckout($data);
        $order = $data['order'];
        $detail = $order->payment->details;
        $detail['checkout_session_id'] = $checkout->id;
        $order->payment()->update([
            'details' => $detail,
        ]);
        return $checkout;
    }

    public function callbackHandler($order): void
    {
        if ($order->payment && $order->payment->type === OrderConstant::PAYMENT_STRIPE) {
            try {
                $stripeCheckout = $this->retrieveCheckout($order->payment->details['checkout_session_id']);
                $detail = $order->payment->details;
                $detail['log'] = [
                    'checkout' => $stripeCheckout,
                ];
                $order->payment()->update([
                    'details' => $detail,
                ]);
                if ($stripeCheckout->payment_status === 'paid') {
                    $orderStatusRepository = new OrderStatusRepository();
                    $order->update([
                        'order_status_id' => $orderStatusRepository->getOrderStatusByTitle(OrderConstant::STATUS_PAID)->id,
                    ]);

                    return;
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
}
