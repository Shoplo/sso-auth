<?php
/**
 * Created by PhpStorm.
 * User: adrianadamiec
 * Date: 12.05.2017
 * Time: 15:19
 */

namespace SSOAuth\Exception;

class ExceptionManager
{
    public static function throwException(\Exception $e)
    {
        switch ($e->getCode()) {
            case 400:
                throw new ValidationException($e);
                break;
            case 404:
                throw new NotFoundException($e);
                break;
            case 500:
                throw new BackendException($e);
                break;
            default:
                throw $e;
        }
    }
}