<?php

namespace rest\api\graphql\components;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use rest\models\Users;

/**
 * Class MutationType
 * @package rest\api\graphql\components
 */
class MutationType extends ObjectType
{
    public function __construct(array $config = [])
    {
        $config = [
            'fields' => function() {
                return [
                    'user' => [
                        'type' => Types::userMutation(),
                        'args' => [
                            'id' => Type::nonNull(Type::int()),
                        ],
                        'resolve' => function($root, $args) {

                            // access (its me)
                            if (\Yii::$app->getUser()->getId() === $args['id']) {
                                return Users::findIdentity($args['id']);
                            }

                            return null;
                        }
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }
}