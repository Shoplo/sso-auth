<?php

namespace SSOAuth;

class SSOAuthClient
{
    /** @var  SSOAuthAdapterInterface */
    private $ssoAuthAdapterInterface;

    public $publicKey;
    public $secretKey;
    public $callbackUrl;
    public $apiBaseUri;
    public $ssoAppId;

    /**
     * SSOAuthClient constructor.
     *
     * @param $ssoAuthAdapterInterface
     * @param $config
     */
    public function __construct($ssoAuthAdapterInterface, $config)
    {
        $this->ssoAuthAdapterInterface = $ssoAuthAdapterInterface;

        $this->publicKey   = $config['publicKey'];
        $this->secretKey   = $config['secretKey'];
        $this->callbackUrl = $config['callbackUrl'];
        $this->apiBaseUri  = $config['apiBaseUrl'];
    }

    public function authorize($returnUrl = false)
    {
        if (isset($_GET['code']) && isset($_GET['app_id'])) {
            $this->ssoAppId = $_GET['app_id'];

            return $this->getAccessToken($_GET['code']);
        } else {
            return $this->requestToken($returnUrl);
        }
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
            'client_id' => $this->publicKey,
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
            'refresh_token' => $refreshToken,
            'client_id'     => $this->publicKey,
            'client_secret' => $this->secretKey,
            'grant_type'    => 'refresh_token',
            'redirect_uri'  => $this->callbackUrl,
        ];

        $response = $this->ssoAuthAdapterInterface->post(
            $this->getAccessTokenUrl($params),
            $params
        );

        return json_decode($response, true);
    }
}