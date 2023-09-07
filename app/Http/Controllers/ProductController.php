<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\CreateProductRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function index(ListProductRequest $request): JsonResponse
    {
        return $this->apiResponse(1, $this->productRepository->getAllProducts($request));
    }

    public function create(CreateProductRequest $request): JsonResponse
    {
        try {
            return $this->apiResponse(1, $this->productRepository->createProduct($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
