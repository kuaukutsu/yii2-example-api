<?php

namespace rest\api\graphql\types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use rest\models\Ideas;

/**
 * Class IdeaType
 * @package rest\api\graphql\types
 */
final class IdeaType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => function() {
                return [

                    'id' => [
                        'type' => Type::string(),
                        'resolve' => function(Ideas $model) {
                            return $model->getId();
                        },
                    ],

                    'head' => [
                        'type' => Type::string(),
                    ],

                    'createAt' => [
                        'type' => Type::string(),
                        'description' => 'Date created',
                        'args' => [
                            'format' => Type::string(),
                        ],
                        'resolve' => function(Ideas $model, $args) {
                            return $model->displayCreateDate(isset($args['format']) ? $args['format'] : null);
                        },
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }
}