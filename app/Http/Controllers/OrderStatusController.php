<?php

namespace App\Http\Controllers;

use App\Repositories\OrderStatusRepository;
use App\Http\Requests\OrderStatus\ListOrderStatusRequest;
use Illuminate\Http\JsonResponse;

class OrderStatusController extends Controller
{
    public function __construct(
        private readonly OrderStatusRepository $orderStatusRepository
    ) {
    }

    public function index(ListOrderStatusRequest $request): JsonResponse
    {
        return $this->apiResponse(1, $this->orderStatusRepository->getAllOrderStatuses($request));
    }
}
