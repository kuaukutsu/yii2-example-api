<?php

namespace rest\components\exceptions;

/**
 * Class MethodNotImplementedException
 * @package rest\components\exceptions
 */
class MethodNotImplementedException extends \BadMethodCallException
{
    /**
     * MethodNotImplementedException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct($message = "", $code = 0)
    {
        parent::__construct('Method not implemented', $code, null);
    }
}