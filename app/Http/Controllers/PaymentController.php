<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Payment\ListPaymentRequest;
use App\Repositories\PaymentRepository;

class PaymentController extends Controller
{

    public function __construct(
        private readonly PaymentRepository $paymentRepository
    )
    {

    }

    public function index(ListPaymentRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->paymentRepository->getAllPayments($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function create(CreatePaymentRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->paymentRepository->createPayment($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
