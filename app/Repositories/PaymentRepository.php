<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Str;
use App\Http\Requests\Payment\ListPaymentRequest;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Interfaces\Repository\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function getAllPayments(ListPaymentRequest $request): LengthAwarePaginator
    {
        $payment = Payment::when($request->get('sortBy'), function (Builder $query) use ($request): void {
            $query->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        });
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
            'details' => $request->details,
        ]);
    }
}
