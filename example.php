<?php
session_start();
require_once __DIR__.'/autoload.php';

ini_set('display_errors', 'TRUE');
error_reporting(E_ALL);

define('SECRET_KEY', 'XXXX');
define('PUBLIC_KEY', 'XXXX');

define('CALLBACK_URL', 'http://127.0.0.1/sso-auth/example.php');

$accessToken = $refreshToken = null;

$config = [
    'apiBaseUrl'   => 'http://auth.shoplo.io',
    'publicKey'    => PUBLIC_KEY,
    'secretKey'    => SECRET_KEY,
    'callbackUrl'  => CALLBACK_URL,
    'accessToken'  => $accessToken,
    'refreshToken' => $refreshToken
];

$guzzleConfig = [
    'base_uri' => 'http://auth.shoplo.io'
];

$guzzleAdapter = new \SSOAuth\Guzzle\GuzzleAdapter(
    new \GuzzleHttp\Client($guzzleConfig)
);
$shoploMultiClient = new \SSOAuth\SSOAuthClient(
    $guzzleAdapter,
    $config
);

$response = $shoploMultiClient->authorize();
print_r($response);
exit;
