<?php

namespace Ghiffariaq\Stripe\Controllers;

use Ghiffariaq\Stripe\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class StripeController extends Controller
{

    public function index()
    {
        return 'test';
    }

    public function callback(Request $request, string $uuid, StripeService $service)
    {
        try {
            //code...
            $service->callbackHandler($uuid);
        } catch (\Throwable $th) {
            Session::flash("error", $th->getMessage());
        }
        return view('stripe::callback');
    }
}
