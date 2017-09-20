<?php

namespace rest\api\graphql\types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use rest\api\graphql\components\Types;
use rest\models\Ideas;
use rest\models\Users;

/**
 * Class UserType
 * @package rest\api\graphql\types
 */
final class UserType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::int(),
                    ],
                    'username' => [
                        'type' => Type::string(),
                    ],
                    'ideas' => [
                        'type' => Type::listOf(Types::idea()),
                        'args' => [
                            'limit' => [
                                'type' => Type::int(),
                                'description' => 'Limit the number of ideas',
                                'defaultValue' => 5
                            ],
                        ],
                        'resolve' => function(Users $model, $args) {
                            if (\Yii::$app->getUser()->getId() === $model->id) {
                                return Ideas::findMeAccess()->limit($args['limit'])->all();
                            }

                            return null;
                        }
                    ]
                ];
            }
        ];

        parent::__construct($config);
    }
}