<?php

namespace App\Interfaces\Repository;

use App\Models\OrderStatus;

interface OrderStatusRepositoryInterface
{

    public function getOrderStatusByTitle(string $title): ?OrderStatus;

}
