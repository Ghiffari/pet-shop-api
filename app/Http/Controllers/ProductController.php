<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\ListProductRequest;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    public function __construct(
        private readonly ProductRepository $productRepository
    )
    {

    }

    public function index(ListProductRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->productRepository->getAllProducts($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }

    public function store(CreateProductRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->productRepository->createProduct($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
