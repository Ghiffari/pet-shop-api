<?php

namespace App\Repositories;

use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Payment\ListPaymentRequest;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Interfaces\Repository\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository implements PaymentRepositoryInterface
{

    public function getAllPayments(ListPaymentRequest $request): LengthAwarePaginator
    {
        $payment = Payment::query();
        if ($request->sortBy) {
            $payment->orderBy($request->sortBy, $request->desc ? "desc" : "asc");
        }
        return $payment->paginate($request->get('limit') ?? 10);
    }

    public function getPaymentByUuid(string $uuid): ?Payment
    {
        return Payment::whereUuid($uuid)->first();
    }

    public function createPayment(CreatePaymentRequest $request): Payment
    {
        return Payment::create([
            'uuid' => Str::uuid(),
            'type' => $request->type,
            'details' => $request->details
        ]);
    }
}
