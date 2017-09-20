<?php

namespace rest\api\graphql\components;

use yii\helpers\Json;
use GraphQL\Error\ClientAware;

/**
 * Class ModelException
 * @package rest\api\graphql\components
 */
class ModelException extends \Exception implements ClientAware
{
    /**
     * SaveException constructor.
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->message = Json::encode($errors);
    }

    /**
     * @return bool
     */
    public function isClientSafe()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'ModelException';
    }
}