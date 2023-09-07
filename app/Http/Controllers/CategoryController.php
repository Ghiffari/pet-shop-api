<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Http\Requests\Category\ListCategoryRequest;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function index(ListCategoryRequest $request)
    {
        try {
            //code...
            return $this->apiResponse(1, $this->categoryRepository->getAllCategories($request));
        } catch (\Throwable $th) {
            return $this->apiResponse(0, $th->getMessage(), method_exists($th, 'getStatusCode') ? $th->getStatusCode() : $th->getCode());
        }
    }
}
