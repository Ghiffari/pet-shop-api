<?php

namespace App\Repositories;

use App\Http\Requests\Category\ListCategoryRequest;
use App\Interfaces\Repository\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(ListCategoryRequest $request): LengthAwarePaginator
    {
        return Category::paginate($request->get('limit') ?? 10);
    }
}
