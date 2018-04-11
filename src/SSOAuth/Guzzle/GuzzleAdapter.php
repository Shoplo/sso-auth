<?php

namespace SSOAuth\Guzzle;

use SSOAuth\Exception\ExceptionManager;
use SSOAuth\SSOAuthAdapterInterface;

class GuzzleAdapter implements SSOAuthAdapterInterface
{
    private $accessToken;
    /** @var  \GuzzleHttp\Client */
    private $guzzle;

    public function __construct(\GuzzleHttp\Client $guzzle, $accessToken = null)
    {
        $this->guzzle      = $guzzle;
        $this->accessToken = $accessToken;
    }

    /**
     * @param null $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getHeaders()
    {
        return ! $this->accessToken
            ? []
            : [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type'  => 'application/json; charset=utf-8',
            ];
    }

    /**
     * @param       $url
     * @param array $data
     * @param array $headers
     *
     * @return string
     * @throws \Exception
     * @throws \SSOAuth\Exception\BackendException
     * @throws \SSOAuth\Exception\NotFoundException
     * @throws \SSOAuth\Exception\ValidationException
     */
    public function post($url, $data = [], $headers = [])
    {
        try {
            $headers = array_merge($headers, $this->getHeaders());
            $rsp     = $this->guzzle->request(
                'POST',
                $url,
                [
                    'auth'        => 'oauth',
                    'headers'     => $headers,
                    'form_params' => $data,
                ]
            );

            return $rsp->getBody()->getContents();
        } catch (\Exception $e) {
            ExceptionManager::throwException($e);
        }
    }
}