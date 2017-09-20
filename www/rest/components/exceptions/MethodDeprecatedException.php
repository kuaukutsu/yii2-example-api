<?php

namespace rest\components\exceptions;

/**
 * Class MethodDeprecatedException
 * @package rest\components\exceptions
 */
class MethodDeprecatedException extends \BadMethodCallException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Method is deprecated';
    }
}