<?php

namespace rest\api\v1\controllers;

use rest\components\exceptions\MethodNotImplementedException;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class IdeaController
 * @package rest\api\v1\controllers
 */
class IdeaController extends AbstractActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // AccessControl
        $behaviors['access'] = [
            'class' => 'yii\filters\AccessControl',
            'rules' => [
                [
                    'actions' => ['help'],
                    'allow' => true,
                    'verbs' => ['GET', 'HEAD']
                ],
                [
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['@'],
                    'verbs' => ['GET']
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                    'verbs' => ['POST']
                ],
            ],
        ];

        // authMethods
        $behaviors['authenticator'] = self::getAuthMethods([], [
            [
                'class' => HttpBearerAuth::className(),
            ]
        ], ['help']);

        return $behaviors;
    }

    /**
     * GET
     * @return array
     */
    public function actionHelp()
    {
        return [
            'help' => [
                'description' => "REST API Help. GET /". $this->module->id ."/" . $this->id . "/help",
            ],
            'GET /'. $this->module->id .'/' . $this->id => [
                'description' => "Get idea list",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'BearerAuthenticated' => 'Authentication method based on HTTP Bearer token'
                ],
                'return' => [
                    'data' => [

                    ]
                ]
            ],
            'GET /'. $this->module->id .'/' . $this->id . '/{id}' => [
                'description' => "Get idea view",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'BearerAuthenticated' => 'Authentication method based on HTTP Bearer token'
                ],
                'params' => [
                    'id' => 'integer'
                ],
                'return' => [
                    'data' => [

                    ]
                ]
            ],
            'POST /'. $this->module->id .'/' . $this->id . '/push' => [
                'description' => "Push new idea",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'BearerAuthenticated' => 'Authentication method based on HTTP Bearer token'
                ],
                'params' => [

                ],
                'return' => [
                    'data' => [

                    ]
                ]
            ],
        ];
    }

    /**
     *
     */
    public function actionIndex()
    {
        throw new MethodNotImplementedException();
    }

    /**
     *
     */
    public function actionView()
    {
        throw new MethodNotImplementedException();
    }

    /**
     *
     */
    public function actionCreate()
    {
        throw new MethodNotImplementedException();
    }
}