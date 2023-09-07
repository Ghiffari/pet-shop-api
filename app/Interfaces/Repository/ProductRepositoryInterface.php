<?php

namespace App\Interfaces\Repository;

use App\Models\Product;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\CreateProductRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getProductByUuid(string $uuid): ?Product;

    public function getAllProducts(ListProductRequest $request): LengthAwarePaginator;

    public function createProduct(CreateProductRequest $request): Product;
}
