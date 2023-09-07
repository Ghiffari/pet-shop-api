<?php

namespace Ghiffariaq\Stripe\Controllers;

use App\Repositories\OrderRepository;
use Ghiffariaq\Stripe\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class StripeController extends Controller
{

    public function __construct(
        private readonly OrderRepository $orderRepository
    ) {
    }

    public function callback(Request $request, string $uuid, StripeService $service)
    {
        $order = $this->orderRepository->getOrderDataByUuid($uuid);
        if ($order) {
            try {
                $service->callbackHandler($order);
            } catch (\Throwable $th) {
                Session::flash("error", $th->getMessage());
            }
            return view('stripe::callback');
        }

        abort(404);
    }
}
