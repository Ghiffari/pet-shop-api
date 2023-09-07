<?php

namespace App\Services;

use DateTimeImmutable;
use Illuminate\Auth\GuardHelpers;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    use GuardHelpers;

    private UserRepository $userRepository;
    private JwtService $jwtService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->jwtService = new JwtService();
    }

    public function user()
    {
        if (request()->bearerToken()) {
            $parsedToken = $this->jwtService->parseToken(request()->bearerToken());
            if ($parsedToken && !$parsedToken->isExpired(new DateTimeImmutable()) && $this->jwtService->getJwtTokenByUniqueId($parsedToken->claims()->get('jti'))) {
                $this->setUser($this->userRepository->getUserByUuid($parsedToken->claims()->get('user_uuid')));
                return $this->user;
            }
        }

        return null;
    }

    public function validate(array $credentials = []): void
    {
    }

    public function setUser(?Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
}
