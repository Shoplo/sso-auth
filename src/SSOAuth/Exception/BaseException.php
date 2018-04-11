<?php
/**
 * Created by PhpStorm.
 * User: adrianadamiec
 * Date: 12.05.2017
 * Time: 15:06
 */

namespace SSOAuth\Exception;

class BaseException extends \Exception
{
    protected $responseParsed;

    public function __construct(\Exception $previous, $body = null)
    {
        $this->responseParsed = serialize($body);
        parent::__construct(
            $previous->getMessage(),
            $previous->getCode(),
            $previous
        );
    }

    /**
     * @return string
     */
    public function getResponseParsed()
    {
        return $this->responseParsed;
    }
}