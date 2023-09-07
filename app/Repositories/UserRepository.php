<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ListUserRequest;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\User\LoginRequest;

use App\Interfaces\Repository\UserRepositoryInterface;
use App\Models\JwtToken;
use App\Models\User;
use App\Util\JwtHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserRepository implements UserRepositoryInterface
{

    use JwtHelper;

    public function login(LoginRequest $request, bool $admin = false): string
    {
        $result = [];

        if (!Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])) {
            throw new UnauthorizedHttpException("Error Processing Request");
        }
        if($admin && !$this->validateAdminRole(Auth::user())){
            throw new AccessDeniedHttpException("Error Processing Request");
        }

        $token = $this->generateToken(Auth::user());

        $this->updateUser(Auth::user(), [
            'last_login_at' => Carbon::now()
        ]);

        return $token->toString();
    }

    public function logout(?string $token): bool
    {
        if(!$token){
            throw new UnauthorizedHttpException("Token not found");
        }

        if ($parsedToken = $this->parseToken($token)) {
            $uniqueId = $parsedToken->claims()->get('jti');
            if($jwtToken = $this->getJwtTokenByUniqueId($uniqueId)){
                $jwtToken->delete();
                return true;
            }
        }

        throw new BadRequestHttpException("Error Processing Request");

    }

    public function getAllUsers(ListUserRequest $request): LengthAwarePaginator
    {
        $users = User::whereIsAdmin(0);

        if($request->get('email')){
            $users->where('email','LIKE', "%" . $request->get('email') .  "%");
        }

        if ($request->get('sortBy')) {
            $users->orderBy($request->get('sortBy'), $request->get('desc') ? "desc" : "asc");
        }

        return $users->paginate($request->limit ?? 10);
    }

    public function getUserByUuid(string $uuid): ?User
    {
        return User::where('uuid',$uuid)->first();
    }

    public function updateUser(User $user, array $data): User
    {
        $updatedData = [];
        if(isset($data['last_login_at'])){
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
