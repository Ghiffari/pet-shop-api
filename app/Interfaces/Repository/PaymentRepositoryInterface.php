<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Payment\ListPaymentRequest;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentRepositoryInterface
{

    public function getAllPayments(ListPaymentRequest $request): LengthAwarePaginator;

    public function getPaymentByUuid(string $uuid): ?Payment;

    public function createPayment(CreatePaymentRequest $request): Payment;
}
