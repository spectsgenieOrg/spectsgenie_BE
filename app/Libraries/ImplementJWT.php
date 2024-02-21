<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ImplementJWT
{
    private $key = 'qwertyuiopasdfzxcv';

    public function GenerateToken($payload)
    {
        $token = JWT::encode((array)$payload, $this->key, 'HS256');
        return $token;
    }

    public function DecodeToken($token)
    {
        $decodedTokenData = JWT::decode($token, new Key($this->key, 'HS256'));
        $decodedData = (array) $decodedTokenData;
        return $decodedData;
    }
}
