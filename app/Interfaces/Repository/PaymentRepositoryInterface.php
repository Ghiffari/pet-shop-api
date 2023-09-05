<?php

namespace App\Interfaces\Repository;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function createPayment(array $data): Payment;
}
