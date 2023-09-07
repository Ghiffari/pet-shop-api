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

        Auth::user()->update([
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
        $users->when($request->get('sortBy'), function (Builder $query) use ($request): void {
            $query->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        });
        if ($request->get('email')) {
            $users->where('email', 'LIKE', "%" . $request->get('email') .  "%");
        }
        return $users->paginate($request->limit ?? 10);
    }

    public function getUserByUuid(string $uuid): ?User
    {
        return User::where('uuid', $uuid)->first();
    }

    private function validateAdminRole(User $user)
    {
        return $user->is_admin;
    }
}
