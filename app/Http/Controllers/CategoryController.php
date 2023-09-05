<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\ListCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    )
    {

    }

    public function index(ListCategoryRequest $request)
    {
        return $this->apiResponse(1, $this->categoryRepository->getAllCategories($request));
    }
}
