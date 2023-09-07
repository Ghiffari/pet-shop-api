<?php

use Ghiffariaq\Stripe\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('payment/{uuid}',[StripeController::class , 'callback'])->name('stripe.callback');
