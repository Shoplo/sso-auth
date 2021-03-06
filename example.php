<?php

//run composer install before this script

session_start();
require_once __DIR__.'/autoload.php';

ini_set('display_errors', 'TRUE');
error_reporting(E_ALL);

define('SECRET_KEY', 'XXXX');
define('PUBLIC_KEY', 'XXXX');

define('CALLBACK_URL', 'http://127.0.0.1/sso-auth/example.php');

$config = [
    'apiBaseUrl'  => 'http://auth.shoplo.io',
    'publicKey'   => PUBLIC_KEY,
    'secretKey'   => SECRET_KEY,
    'callbackUrl' => CALLBACK_URL,
];

$guzzleConfig = [
    'base_uri' => 'http://auth.shoplo.io',
];

$guzzleAdapter     = new \SSOAuth\Guzzle\GuzzleAdapter(
    new \GuzzleHttp\Client($guzzleConfig)
);
$shoploMultiClient = new \SSOAuth\SSOAuthClient(
    $guzzleAdapter,
    $config
);

$response = $shoploMultiClient->authorize();
echo $shoploMultiClient->ssoAppId;
print_r($response);
exit;
