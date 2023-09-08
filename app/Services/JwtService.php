<?php

namespace App\Services;

use App\Models\User;
use DateTimeImmutable;
use App\Models\JwtToken;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;

class JwtService
{
    public function generateToken(User $user): Plain
    {
        $tokenBuilder = $this->createTokenBuilder();
        $token = $this->buildToken($tokenBuilder, $user);
        $this->saveTokenToDatabase($user, $token);
        return $token;
    }

    private function createTokenBuilder(): Builder
    {
        return (new Builder(new JoseEncoder(), ChainedFormatter::default()))
        ->issuedBy(config('app.url'))
        ->permittedFor(config('app.url'))
        ->identifiedBy(uniqid())
        ->issuedAt(new DateTimeImmutable())
        ->canOnlyBeUsedAfter(new DateTimeImmutable())
        ->expiresAt((new DateTimeImmutable())->modify('+2 hours'));
    }

    private function saveTokenToDatabase(User $user, Plain $token): void
    {
        JwtToken::create([
            'user_id' => $user->id,
            'unique_id' => $token->claims()->get('jti'),
            'token_title' => "Token authentication",
        ]);
    }


    private function buildToken(Builder $tokenBuilder, User $user): Plain
    {
        $algorithm = new Sha256();
        $signingKey = InMemory::file(config('auth.jwt.private_key_path'));

        return $tokenBuilder
            ->withClaim('user_uuid', $user->uuid)
            ->getToken($algorithm, $signingKey);
    }

    public function parseToken(string $token): ?Plain
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $parsedToken = $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound) {
            $parsedToken = null;
        }

        return $parsedToken;
    }

    public function getJwtTokenByUniqueId(?string $uniqueId): ?JwtToken
    {
        return JwtToken::whereUniqueId($uniqueId)->first();
    }
}
