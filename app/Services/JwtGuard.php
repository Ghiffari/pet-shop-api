<?php

namespace App\Services;


use Illuminate\Auth\GuardHelpers;
use App\Repositories\UserRepository;
use App\Util\JwtHelper;
use DateTimeImmutable;
use Illuminate\Contracts\Auth\Guard;

use Illuminate\Contracts\Auth\Authenticatable;


class JwtGuard implements Guard
{
    use GuardHelpers, JwtHelper;

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        if (!empty($token = request()->bearerToken())) {
            if($parsedToken = $this->parseToken($token)){
                if(!$parsedToken->isExpired(new DateTimeImmutable()) && $this->getJwtTokenByUniqueId($parsedToken->claims()->get('jti'))){
                    if($user = $this->userRepository->getUserByUuid($parsedToken->claims()->get('user_uuid'))){
                        return $this->setUser($user);
                    };
                }

            }
        }

        return null;
    }

    public function validate(array $credentials = [])
    {

    }

    public function setUser(?Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }


}
