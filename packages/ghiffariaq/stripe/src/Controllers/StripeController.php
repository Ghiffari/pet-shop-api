<?php

namespace Ghiffariaq\Stripe\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StripeController extends Controller
{
    public function index()
    {
        return 'test';
    }

    public function callback(Request $request)
    {
        // TODO ADD CALLBACK LOGIC
        return view('stripe::callback');
    }
}
