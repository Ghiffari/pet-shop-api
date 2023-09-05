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
        return $this->apiResponse(1, $this->productRepository->getAllProducts($request));
    }

    public function store(CreateProductRequest $request)
    {
        try {
            return $this->apiResponse(1, $this->productRepository->createProduct($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->__toString(), $th->getCode() > 0 ? $th->get_code : Response::HTTP_BAD_REQUEST);
        }
    }
}
