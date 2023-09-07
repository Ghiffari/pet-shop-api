<?php

namespace App\Constants;

class OrderConstant
{
    public const STATUS_OPEN = "open";
    public const STATUS_PENDING_PAYMENT = "pending payment";
    public const STATUS_PAID = "paid";
    public const STATUS_SHIPPED = "shipped";
    public const STATUS_CANCELLED = "cancelled";

    public const PAYMENT_CREDIT_CARD = "credit_card";
    public const PAYMENT_CASH_ON_DELIVERY = "cash_on_delivery";
    public const PAYMENT_BANK_TRANSFER = "bank_transfer";
    public const PAYMENT_STRIPE = "stripe";

    public const LIST_OF_PAYMENTS = [
        self::PAYMENT_BANK_TRANSFER,
        self::PAYMENT_CASH_ON_DELIVERY,
        self::PAYMENT_CREDIT_CARD,
        self::PAYMENT_STRIPE,
    ];

    public const LIST_OF_USER_STATUS_UPDATE = [
        self::STATUS_OPEN,
        self::STATUS_PENDING_PAYMENT,
    ];
}
