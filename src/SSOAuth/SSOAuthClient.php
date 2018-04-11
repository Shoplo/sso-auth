<?php

namespace SSOAuth;

class SSOAuthClient
{
    /** @var  SSOAuthAdapterInterface */
    private $ssoAuthAdapterInterface;

    public $publicKey;
    public $secretKey;
    public $callbackUrl;
    public $accessToken;
    public $refreshToken;
    public $apiBaseUri;
    public $scope;

    /**
     * SSOAuthClient constructor.
     *
     * @param $ssoAuthAdapterInterface
     * @param $config
     */
    public function __construct($ssoAuthAdapterInterface, $config)
    {
        $this->ssoAuthAdapterInterface = $ssoAuthAdapterInterface;

        $this->publicKey    = $config['publicKey'];
        $this->secretKey    = $config['secretKey'];
        $this->callbackUrl  = $config['callbackUrl'];
        $this->accessToken  = $config['accessToken'];
        $this->refreshToken = $config['refreshToken'];
        $this->apiBaseUri   = $config['apiBaseUrl'];
        $this->scope        = $config['scope'];
    }

    public function authorize($returnUrl = false)
    {
        if (isset($_GET['code'])) {
            return $this->getAccessToken($_GET['code']);
        } else {
            return $this->requestToken($returnUrl);
        }
    }

    private function getUserUrl()
    {
        return '/v1/public/me';
    }

    public function getUser()
    {
        $this->ssoAuthAdapterInterface->get($this->getUserUrl());
    }

    private function getRequestTokenUrl()
    {
        return $this->apiBaseUri;
    }

    private function getAccessTokenUrl($params)
    {
        $query = http_build_query($params);

        return '/oauth/token?'.$query;
    }

    public function getAccessToken($code)
    {
        $params = [
            'client_id'     => $this->publicKey,
            'client_secret' => $this->secretKey,
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->callbackUrl,
        ];

        $response = $this->ssoAuthAdapterInterface->post(
            $this->getAccessTokenUrl($params),
            $params
        );

        return json_decode($response, true);
    }

    public function requestToken($returnUrl = false)
    {
        $queryParameters = [
            'clientId' => $this->publicKey,
//            'client_id' => $this->publicKey,
//            'redirect_uri' => $this->callbackUrl,
//            'response_type' => 'code',
//            'scope' => $this->scope,
//            'state' => 6960840,
        ];

        $query   = http_build_query($queryParameters);
        $authUrl = $this->getRequestTokenUrl().'?'.$query;

        if ($returnUrl) {
            return $authUrl;
        }
        header("Location: {$authUrl}");
        exit;
    }

    public function refreshToken($refreshToken)
    {
        $params = [
//            'clientId' => $this->publicKey,
            'refresh_token' => $refreshToken,
            'client_id'     => $this->publicKey,
            'client_secret' => $this->secretKey,
            'grant_type'    => 'refresh_token',
            'redirect_uri'  => $this->callbackUrl,
        ];

        $response = $this->ssoAuthAdapterInterface->get(
            $this->getAccessTokenUrl($params),
            $params
        );

        return json_decode($response, true);
    }
}