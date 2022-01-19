<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


const JWT_Key = "jaimelesharicots";

function Get_JWT_Secret() 
{
    return JWT_Key;
}

function Get_Encoded_JWT_Token(Account $pAccount)
{
    $issuedAt = time();
    $payload = array(
        "iat" => $issuedAt,
        "exp" => $issuedAt + 600,
        "sub" => $pAccount->getId(),
        "_email" => $pAccount->getEmail()
    );

    $jwt_token = JWT::encode($payload, JWT_Key, "HS512");
    return $jwt_token;
}

function Decode_JWT_Token($token)
{
    $decoded = JWT::decode($token, new Key($key, 'HS512'));
    return (array) $decoded;
}
