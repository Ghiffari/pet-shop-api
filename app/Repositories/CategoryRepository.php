<?php

namespace App\Repositories;

use App\Models\Category;
use App\Http\Requests\Category\ListCategoryRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Repository\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(ListCategoryRequest $request): LengthAwarePaginator
    {
        $category = Category::query();
        if ($request->sortBy) {
            $category->orderBy($request->sortBy, $request->desc ? "desc" : "asc");
        }
        return $category->paginate($request->get('limit') ?? 10);
    }
}
