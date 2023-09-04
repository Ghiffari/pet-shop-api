<?php

namespace App\Services;

use Lcobucci\JWT\Token\Parser;
use Illuminate\Auth\GuardHelpers;
use App\Repositories\UserRepository;
use DateTimeImmutable;
use Illuminate\Contracts\Auth\Guard;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Illuminate\Contracts\Auth\Authenticatable;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;

class JwtGuard implements Guard
{
    use GuardHelpers;

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
                if(!$parsedToken->isExpired(new DateTimeImmutable())){
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

    private function parseToken(string $token): ?Plain
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $parsedToken = $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            $parsedToken = null;
        }

        return $parsedToken;
    }
}
