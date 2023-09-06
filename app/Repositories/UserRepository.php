<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ListUserRequest;
use DateTimeImmutable;
use Illuminate\Http\Response;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Encoding\JoseEncoder;
use App\Http\Requests\User\LoginRequest;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Models\JwtToken;
use App\Models\User;
use App\Util\JwtHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lcobucci\JWT\Token\Parser;
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

        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::file(config('auth.jwt.private_key_path'));

        $now   = new DateTimeImmutable();
        $uniqueId = uniqid();
        $token = $tokenBuilder
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy($uniqueId)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+2 hours'))
            ->withClaim('user_uuid', Auth::user()->uuid)
            ->getToken($algorithm, $signingKey);

        JwtToken::create([
            'user_id' => Auth::user()->id,
            'unique_id' => $uniqueId,
            'token_title' => "Token authentication"
        ]);

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
