<?php

use Illuminate\Support\Facades\Route;
use Ghiffariaq\Stripe\Controllers\StripeController;

Route::get('payment/{uuid}', [StripeController::class, 'callback'])->name('stripe.callback');
