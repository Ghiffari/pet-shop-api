<?php

namespace App\Constants;

class OrderConstant
{
    const STATUS_OPEN = "open";
    const STATUS_PENDING_PAYMENT = "pending payment";
    const STATUS_PAID = "paid";
    const STATUS_SHIPPED = "shipped";
    const STATUS_CANCELLED = "cancelled";

    const PAYMENT_CREDIT_CARD = "credit_card";
    const PAYMENT_CASH_ON_DELIVERY = "cash_on_delivery";
    const PAYMENT_BANK_TRANSFER = "bank_transfer";
    const PAYMENT_STRIPE = "stripe";

    const LIST_OF_PAYMENTS = [
        self::PAYMENT_BANK_TRANSFER,
        self::PAYMENT_CASH_ON_DELIVERY,
        self::PAYMENT_CREDIT_CARD,
        self::PAYMENT_STRIPE
    ];

    const LIST_OF_USER_STATUS_UPDATE = [
        self::STATUS_OPEN,
        self::STATUS_PENDING_PAYMENT,
    ];
}
