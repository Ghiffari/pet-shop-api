<?php

namespace App\Util;

use App\Models\JwtToken;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;

trait JwtHelper
{
    public function parseToken(string $token): ?Plain
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $parsedToken = $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            $parsedToken = null;
        }

        return $parsedToken;
    }

    public function getJwtTokenByUniqueId(?string $uniqueId): ?JwtToken
    {
        return JwtToken::whereUniqueId($uniqueId)->first();
    }
}
