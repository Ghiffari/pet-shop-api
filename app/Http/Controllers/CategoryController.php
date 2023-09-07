<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Http\Requests\Category\ListCategoryRequest;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function index(ListCategoryRequest $request): JsonResponse
    {
        return $this->apiResponse(1, $this->categoryRepository->getAllCategories($request));
    }
}
