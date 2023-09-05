<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Str;
use App\Interfaces\Repository\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function createPayment(array $data): Payment
    {
        return Payment::create([
            'uuid' => Str::uuid(),
            'type' => $data['payment']['type'],
            'details' => $data['payment']['details']
        ]);
    }
}
