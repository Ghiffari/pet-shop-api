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
    private JwtService $jwtService;

    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    public function login(LoginRequest $request, bool $admin = false): string
    {
        $this->authenticate($request);
        $this->validateRole(Auth::user(), $admin);
        $token = $this->jwtService->generateToken(Auth::user());
        Auth::user()->update([
            'last_login_at' => Carbon::now(),
        ]);
        return $token->toString();
    }

    private function authenticate(LoginRequest $request): void
    {
        if (!Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ])) {
            throw new UnauthorizedHttpException("Error Processing Request");
        }
    }

    public function logout(?string $token): void
    {
        $parsedToken = $this->jwtService->parseToken($token);
        $uniqueId = $parsedToken->claims()->get('jti');
        $jwtToken = $this->jwtService->getJwtTokenByUniqueId($uniqueId);
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

    private function validateRole(User $user, bool $admin): void
    {
        if ($admin && !$user->is_admin) {
            throw new AccessDeniedHttpException("Error Processing Request");
        }
    }
}
