<?php

namespace App\Repositories;

use App\Models\Category;
use App\Http\Requests\Category\ListCategoryRequest;
use App\Interfaces\Repository\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(ListCategoryRequest $request): LengthAwarePaginator
    {
        $category = Category::when($request->get('sortBy'), function (Builder $query) use ($request): void {
            $query->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        });
        return $category->paginate($request->get('limit') ?? 10);
    }
}
