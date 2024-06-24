<?php

use Module\comm\member\Models\MemberModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


if (!function_exists('_getUserInfo')) {
    function _getUserInfo()
    {
        $request = Services::request();
        $authenticationHeader = $request->getHeaderLine('Authorization');

        if ($authenticationHeader) {
            $token = getJWTFromRequest($authenticationHeader);

            $aReturn = validateJWTFromRequest($token);

            $decodedToken = decodeToken($token);
            $GLOBALS['userInfo'] = $aReturn;


            if (!isset($decodedToken->error)) {
                return (array) $decodedToken;
            }
        }

        return null;
    }
}

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request11');
    }
    $tokenInfo = explode(' ', $authenticationHeader)[1];

    $uri = current_url(true);

    if( strpos( $uri, '/download?' ) == false){
        checkPermitions( $tokenInfo );
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return $tokenInfo;
    //return $authenticationHeader;
}

function checkPermitions( string $encodedToken )
{
    $aReturn = [];
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    if( $decodedToken->data->perm != 'user' ){
        throw new Exception('permition denined');
        exit;
    }
}


function decodeToken(string $encodedToken)
{
    $aReturn = [];
    $key = Services::getSecretKey();

    try {
        $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    } catch (Exception $e) {
        $aReturn['error'] = $e->getMessage();
        return $aReturn;
    }

    return $decodedToken->data;
}

function validateJWTFromRequest(string $encodedToken)
{
    $aReturn = [];
    $key = Services::getSecretKey();

    try {
        $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
        $memberModel = new MemberModel();
        $modelParam = [];
        $_decodedToken = json_decode(json_encode($decodedToken), true);


        if(empty($_decodedToken['data']['uid']) === false) {
            $modelParam['u_id']         = $_decodedToken['data']['uid'];
            $modelParam['u_pwd']        = 'jwtAuth';
            $modelParam['pass_login']   = true;
        }

        $aReturn = $memberModel->findUserById($modelParam);

        if( empty($aReturn) === true  ){
            throw new Exception('User does not exist for specified userid');
        }
    } catch (Exception $e) {
        $aReturn['error'] = $e->getMessage();
        return $aReturn;
    }


    return $aReturn;
}

function setAccessToken(array $data)
{
    date_default_timezone_set('Asia/Seoul');

    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');

    $now = time();
    $exp = $now + $tokenTimeToLive;
    $payload = [
        "iss"  => $_SERVER["SERVER_NAME"],
        "aud"  => "timber",
        "sub"  => '',
        "nbf"  => $now,
        'iat'  => $now,
        'exp'  => $exp,
        "data" => array(
            'uid'          => _elm( $data, 'MB_USERID' ),
            'sessionId'    => session_id(),
            'perm'         => 'user'
        )
    ];


    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');
    return $jwt;
}

function setRefreshToken(array $data)
{
    date_default_timezone_set('Asia/Seoul');

    $tokenTimeToLive = getenv('JWT_TIME_TO_REFRESH');

    $now = time();
    $exp = _elm( $data, 'isAutoSignin' ) === 'true' ? $now + ($tokenTimeToLive * 21)  : $now + $tokenTimeToLive;
    $payload = [
        "iss"  => $_SERVER["SERVER_NAME"],
        "aud"  => "timber",
        "sub"  => '',
        "nbf"  => $now,
        'iat'  => $now,
        'exp'  => $exp,
        "data" => array(
            'uid'          => _elm( $data, 'MB_USERID' ),
            'sessionId'    => session_id(),
            'perm'         => 'user'
        )
    ];


    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');
    return $jwt;
}


function getSignedJWTForUser(array $data)
{
    date_default_timezone_set('Asia/Seoul');

    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');

    $now = time();
    $exp = $now + $tokenTimeToLive;
    $payload = [
        "iss" => $_SERVER["SERVER_NAME"],
        "aud" => "timber",
        "sub" => _elm( $data, 'MB_USERID' )."_JWT",
        "nbf" => $now,
        'iat' => $now,
        'exp' => $exp,
        "data" => array(
            'uid' => _elm( $data, 'MB_USERID' ),
            'sessionId'    => session_id(),
            'perm'         => 'user'
        )
    ];


    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');
    return $jwt;
}

function getRefreshJWTForUser(array $data)
{
    date_default_timezone_set('Asia/Seoul');

    $tokenTimeToLive = getenv('JWT_TIME_TO_REFRESH');
    $now = time();
    $exp = $now + $tokenTimeToLive;
    $payload = [
        "iss" => $_SERVER["SERVER_NAME"],
        "aud" => "timber",
        "sub" => _elm( $data, 'MB_USERID' )."_JWT",
        "nbf" => $now,
        'iat' => $now,
        'exp' => $exp,
        "data" => array(
            'uid' => _elm( $data, 'MB_USERID' ),
            'sessionId'    => session_id(),
            'perm'         => 'user'
        )
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');
    return $jwt;
}

function jwtAccessTokenExpired($token)
{
    $secret_key =  Services::getSecretKey();

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256') );
        $current_time = time();

        if ($decoded->exp < $current_time) {
            return true; // Access token is expired
        } else {
            return false; // Access token is still valid
        }
    } catch (Exception $e) {
        return false; // Error decoding the token
    }
}