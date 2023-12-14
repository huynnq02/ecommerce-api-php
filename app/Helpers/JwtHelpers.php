<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;

function generateRefreshToken()
{
    $data = [
        'sub' =>  auth('api')->user()->getAuthIdentifier(),
        'random' => rand() . time(),
        'exp' => time() + config('jwt.refresh_ttl')
    ];
    $refreshToken = JWTAuth::getJWTProvider()->encode($data);
    return $refreshToken;
}
