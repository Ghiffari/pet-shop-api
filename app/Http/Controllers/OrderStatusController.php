<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStatus\ListOrderStatusRequest;
use App\Repositories\OrderStatusRepository;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{

    public function __construct(
        private readonly OrderStatusRepository $orderStatusRepository
    )
    {

    }

    public function index(ListOrderStatusRequest $request)
    {
        return $this->apiResponse(1, $this->orderStatusRepository->getAllOrderStatuses($request));
    }
}
