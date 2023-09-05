<?php

namespace Ghiffariaq\Stripe\Interfaces;

interface StripeServiceInterface
{
    public function generateCheckoutUrl($data): string;
}
