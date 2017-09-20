<?php

namespace rest\api\graphql\components;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ErrorType
 * @package rest\api\graphql\components
 */
class ErrorType extends ObjectType
{
    public function __construct(array $config = [])
    {
        $config = [
            'fields' => function() {
                return [
                    'field' => Type::string(),
                    'messages' => Type::listOf(Type::string()),
                ];
            },
        ];

        parent::__construct($config);
    }
}