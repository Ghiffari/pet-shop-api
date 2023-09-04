<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function login(LoginRequest $request, bool $admin = false): array;

    public function getAllUsers(Request $request): array;

    public function getUserByUuid(string $uuid): ?User;
}
