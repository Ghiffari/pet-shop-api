<?php

namespace Ghiffariaq\Stripe\Services;

use Ghiffariaq\Stripe\Interfaces\StripeServiceInterface;
use Ghiffariaq\Stripe\Util\StripeClient;

class StripeService implements StripeServiceInterface
{
    use StripeClient;

    public function generateCheckoutUrl($data): string
    {
        return $this->createCheckout($data)->url;
    }
}
