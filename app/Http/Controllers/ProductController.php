<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\CreateProductRequest;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function index(ListProductRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->productRepository->getAllProducts($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function create(CreateProductRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->productRepository->createProduct($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
