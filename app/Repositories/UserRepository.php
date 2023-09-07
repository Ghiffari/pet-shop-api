<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\Admin\ListUserRequest;
use App\Interfaces\Repository\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserRepository implements UserRepositoryInterface
{
    public function login(LoginRequest $request, bool $admin = false): string
    {
        $result = [];

        if (!Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ])) {
            throw new UnauthorizedHttpException("Error Processing Request");
        }
        if ($admin && !$this->validateAdminRole(Auth::user())) {
            throw new AccessDeniedHttpException("Error Processing Request");
        }
        $service = new JwtService();
        $token = $service->generateToken(Auth::user());

        $this->updateUser(Auth::user(), [
            'last_login_at' => Carbon::now(),
        ]);

        return $token->toString();
    }

    public function logout(?string $token): void
    {
        $service = new JwtService();
        $parsedToken = $service->parseToken($token);
        $uniqueId = $parsedToken->claims()->get('jti');
        $jwtToken = $service->getJwtTokenByUniqueId($uniqueId);
        $jwtToken->delete();
    }

    public function getAllUsers(ListUserRequest $request): LengthAwarePaginator
    {
        $users = User::whereIsAdmin(0);

        if ($request->get('email')) {
            $users->where('email', 'LIKE', "%" . $request->get('email') .  "%");
        }

        if ($request->get('sortBy')) {
            $users->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        }

        return $users->paginate($request->limit ?? 10);
    }

    public function getUserByUuid(string $uuid): ?User
    {
        return User::where('uuid', $uuid)->first();
    }

    public function updateUser(User $user, array $data): User
    {
        $updatedData = [];
        if (isset($data['last_login_at'])) {
            $updatedData['last_login_at'] = $data['last_login_at'];
        }
        $user->update($updatedData);
        return $user;
    }

    private function validateAdminRole(User $user)
    {
        return $user->is_admin;
    }
}
