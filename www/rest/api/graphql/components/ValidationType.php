<?php

namespace rest\api\graphql\components;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ValidationType
 * @package rest\api\graphql\components
 */
class ValidationType extends ObjectType
{
    public function __construct(array $config = [])
    {
        $config = [
            'fields' => function() {
                return [
                    'errors' => Type::listOf(Types::error()),
                ];
            },
        ];

        parent::__construct($config);
    }
}