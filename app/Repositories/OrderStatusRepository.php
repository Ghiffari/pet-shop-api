<?php

namespace App\Repositories;

use App\Interfaces\Repository\OrderStatusRepositoryInterface;
use App\Models\OrderStatus;

class OrderStatusRepository implements OrderStatusRepositoryInterface
{
    public function getOrderStatusByTitle(string $title): ?OrderStatus
    {
        return OrderStatus::whereTitle($title)->first();
    }
}
