<?php

namespace rest\components\exceptions;

/**
 * Class AccessDeniedException
 * @package rest\components\exceptions
 */
class AccessDeniedException extends \UnexpectedValueException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Access denied';
    }
}