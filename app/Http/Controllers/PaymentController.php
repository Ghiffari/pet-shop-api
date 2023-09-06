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
        return $this->apiResponse(1, $this->paymentRepository->getAllPayments($request));
    }

    public function create(CreatePaymentRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->paymentRepository->createPayment($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), $th->getCode() > 0 ? $th->get_code : Response::HTTP_BAD_REQUEST);
        }
    }
}
