<?php

namespace Ghiffariaq\Stripe\Interfaces;

use App\Models\Order;

interface StripeServiceInterface
{
    public function generateCheckoutData($data): mixed;

    public function callbackHandler(Order $order): void;
}
