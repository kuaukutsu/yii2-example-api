<?php

namespace rest\api\graphql\components;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use rest\models\Users;

/**
 * Class QueryType
 * @package rest\api\graph\types
 */
class QueryType extends ObjectType
{
    public function __construct(array $config = [])
    {
        $config = [
            'fields' => function() {
                return [
                    'user' => [
                        'type' => Types::user(),
                        'args' => [
                            'id' => Type::nonNull(Type::int()),
                        ],
                        'resolve' => function($root, $args) {

                            /** @var Users $identity */
                            $identity = Users::findIdentity(\Yii::$app->getUser()->getId());
                            if ($identity === null) {
                                return null;
                            }

                            // access (its me)
                            if ($identity->id === $args['id']) {
                                return Users::findIdentity($args['id']);
                            }

                            return null;
                        }
                    ],

                    'idea' => [
                        'type' => Types::idea(),
                        'args' => [
                            'id' => Type::nonNull(Type::string()),
                        ],
                        'resolve' => function($root, $args) {
                            return [];
                        }
                    ],

                    'ideas' => [
                        'type' => Type::listOf(Types::idea()),
                        'args' => [
                            'limit' => [
                                'type' => Type::int(),
                                'description' => 'Limit the number of ideas',
                                'defaultValue' => 10
                            ],
                            // sort, field_sort ...
                        ],
                        'resolve' => function() {
                            return;
                        }
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }
}