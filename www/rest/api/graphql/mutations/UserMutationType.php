<?php

namespace rest\api\graphql\mutations;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use rest\api\graphql\components\ModelException;
use rest\api\graphql\components\Types;
use rest\models\Users;

/**
 * Class UserMutationType
 * @package rest\api\graphql\mutations
 */
final class UserMutationType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => function() {
                return [
                    // method one
                    'update' => [
                        'type' => Types::verifyValidation(Types::user()),
                        'args' => [
                            'username' => Type::string()
                        ],
                        'resolve' => function(Users $user, $args) {
                            $user->setAttributes($args);
                            $user->save();

                            if ($user->hasErrors()) {
                                $errors = [];
                                foreach ($user->getErrors() as $field => $messages) {
                                    $errors[] = [
                                        'field' => $field,
                                        'messages' => $messages,
                                    ];
                                }

                                return ['errors' => $errors];
                            }

                            return $user;
                        }
                    ],
                    // method two
                    'save' => [
                        'type' => Types::user(),
                        'args' => [
                            'input' => [
                                'type' => Type::nonNull(
                                    new InputObjectType([
                                        'name' => 'UserInput',
                                        'fields' => [
                                            'username' => [
                                                'type' => Type::string()
                                            ]
                                        ]
                                    ])),
                            ]
                        ],
                        'resolve' => function(Users $user, $args) {
                            $user->setAttributes($args['input']);
                            $user->save();

                            if ($user->hasErrors()) {
                                throw new ModelException($user->getErrors());
                            }

                            return $user;
                        }
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }
}