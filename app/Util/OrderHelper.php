<?php

namespace App\Util;

use App\Repositories\ProductRepository;

trait OrderHelper
{

    public function calculateOrderAmount(array $products): float
    {
        $amount = floatval(0);
        $productRepository = new ProductRepository();
        foreach ($products as $product) {
            $res = $productRepository->getProductByUuid($product['product']);
            if ($res) {
                $amount += ($res->price * $product['quantity']);
            }
        }

        return $amount;
    }
}
