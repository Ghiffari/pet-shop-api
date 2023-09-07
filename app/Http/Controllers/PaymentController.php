<?php

namespace App\Http\Controllers;

use App\Repositories\PaymentRepository;
use App\Http\Requests\Payment\ListPaymentRequest;
use App\Http\Requests\Payment\CreatePaymentRequest;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository
    ) {
    }

    public function index(ListPaymentRequest $request): JsonResponse
    {
        return $this->apiResponse(1, $this->paymentRepository->getAllPayments($request));
    }

    public function create(CreatePaymentRequest $request): JsonResponse
    {
        try {
            return $this->apiResponse(1, $this->paymentRepository->createPayment($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
