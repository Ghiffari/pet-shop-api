<?php

namespace App\Interfaces\Repository;

use App\Models\User;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\Admin\ListUserRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function login(LoginRequest $request, bool $admin = false): string;

    public function logout(?string $token): void;

    public function getAllUsers(ListUserRequest $request): LengthAwarePaginator;

    public function getUserByUuid(string $uuid): ?User;

    public function updateUser(User $user, array $data): User;
}
