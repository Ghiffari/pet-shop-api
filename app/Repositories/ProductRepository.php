<?php

namespace App\Repositories;

use App\Models\Product;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\CreateProductRequest;
use App\Interfaces\Repository\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProductByUuid(string $uuid): Product
    {
        return Product::where('uuid', $uuid)->first();
    }

    public function getAllProducts(ListProductRequest $request): LengthAwarePaginator
    {
        $products = Product::with('category');
        if ($request->get('sortBy')) {
            $products->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        }
        return $products->paginate($request->get('limit') ?? 10);
    }

    public function createProduct(CreateProductRequest $request): Product
    {
        return Product::create([
            'category' => $request->get('category_uuid'),
            'title' => $request->get('title'),
            'price' => $request->get('price'),
            'metadata' => [],
        ]);
    }
}
