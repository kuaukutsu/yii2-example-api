<?php

namespace rest\api\graphql\components;

use yii\helpers\Json;
use GraphQL\Error\Error;

/**
 * Class FormattedError
 * @package rest\api\graphql\components
 */
class FormattedError extends \GraphQL\Error\FormattedError
{
    /**
     * @param \Throwable $e
     * @param bool $debug
     * @param null $internalErrorMessage
     * @return array
     */
    public static function createFromException($e, $debug = false, $internalErrorMessage = null)
    {
        if ($e instanceof Error && $e->getCategory() === 'ModelException') {

            $formattedError = [];
            foreach (Json::decode($e->getMessage()) as $field => $message) {
                array_push($formattedError, [
                    'name' => $field,
                    'message' => $message
                ]);
            }

            return $formattedError;
        }

        return parent::createFromException($e, $debug, $internalErrorMessage);
    }
}