<?php

return [
    'id' => 'example-api',
    'charset' => 'UTF-8',
    'timeZone' => 'UTC', // Europe/Moscow
    'basePath' => dirname(dirname(__DIR__)),

    'modules' => [
        'v1' => [
            'class' => 'rest\api\v1\Module',
        ],
        'ajax' => [
            'class' => 'rest\api\ajax\Module',
        ],
        'graphql' => [
            'class' => 'rest\api\graphql\Module',
        ]
    ],

    'components' => [

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'user' => [
            'class' => 'yii\web\User',
            'loginUrl' => '/user/login',
            'identityClass' => 'rest\models\Users',
            'enableAutoLogin' => false
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                // GraphQL
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => [
                        'graphql' => 'graphql/default'
                    ],
                    'patterns' => [
                        '' => 'index'
                    ],
                ],
                // AJAX
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:[\d]+>',
                        '{action}' => '<action:[\w\d\-]+>'
                    ],
                    'controller' => [
                        'ajax/user',
                        'ajax/idea',
                        'ajax/comment'
                    ],
                    'patterns' => [
                        'DELETE {id}' => 'delete',
                        'POST {id}/{action}' => '<action>',
                        'POST {id}' => 'update',
                        'POST {action}' => '<action>',
                        'POST' => 'create',
                        'GET {id}/{action}' => '<action>',
                        'GET {id}' => 'view',
                        'GET {action}' => '<action>',
                        'GET'  => 'index',
                        '' => 'options',
                    ],
                ],
                // API v1
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:[\d]+>',
                        '{action}' => '<action:[\w\-]+>',
                        '{controller}' => '<controller:[\w\-]+>'
                    ],
                    'controller' => [
                        'v1/auth',
                        'v1/user',
                        'v1/idea',
                    ],
                    /*'patterns' => [
                        'PUT,PATCH {id}' => 'update',
                        'PUT,PATCH {id}/{action}' => 'update',
                        'PUT,PATCH {action}' => '<action>',
                        'DELETE {id}' => 'delete',
                        'GET,HEAD {id}' => 'view',
                        'GET {id}/{action}' => 'view',
                        'POST {id}' => 'update',
                        'GET,POST {action}' => '<action>',
                        'POST' => 'create',
                        'GET,HEAD' => 'index',
                        '{id}' => 'options',
                        '' => 'options',
                    ],*/

                    'patterns' => [
                        'PUT,PATCH,POST {id}/{action}' => 'update',
                        'PUT,PATCH,POST {id}' => 'update',
                        'PUT,PATCH,POST {action}' => '<action>',
                        'PUT,PATCH,POST {controller}/{id}/{action}' => '<controller>/update',
                        'PUT,PATCH,POST {controller}/{id}' => '<controller>/update',
                        'PUT,PATCH,POST {controller}/{action}' => '<controller>/<action>',
                        'DELETE {id}' => 'delete',
                        'DELETE {controller}/{id}' => '<controller>/delete',
                        'POST' => 'create',
                        'POST {controller}' => '<controller>/create',
                        'GET {section}/{action}' => '<action>',
                        'GET {id}/{action}' => 'view',
                        'GET {controller}/{section}/{action}' => '<controller>/<action>',
                        'GET {controller}/{id}/{action}' => '<controller>/view',
                        'GET {id}' => 'view',
                        'GET {controller}/{id}' => '<controller>/view',
                        'GET {action}' => '<action>',
                        'GET {controller}/{action}' => '<controller>/<action>',
                        'GET' => 'index',
                        'GET <controller:[\w\-]+>' => '<controller>/index',
                        '{id}' => 'options',
                        '' => 'options'
                    ],

                    'extraPatterns' => [
                        'GET help' => 'help'
                    ],
                ],
            ],
        ],

        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'response' => [
            'class' => 'rest\components\Response',
            'format' => \rest\components\Response::FORMAT_JSON,
            'formatters' => [
                'json' => [
                    'class' => 'rest\components\JsonResponseFormatter',
                    'useJsonr' => true
                ],
            ],
        ],
    ],

    'params' => array_merge(
        require(__DIR__ . '/../../config/params.php'),
        require(__DIR__ . '/params.php')
    ),
];
