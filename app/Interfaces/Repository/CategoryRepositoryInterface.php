<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\Category\ListCategoryRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAllCategories(ListCategoryRequest $request): LengthAwarePaginator;
}
