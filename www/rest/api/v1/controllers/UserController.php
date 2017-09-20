<?php

namespace rest\api\v1\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use rest\components\exceptions\MethodNotImplementedException;
use rest\api\v1\models\Users;

/**
 * Class UserController
 * @package rest\api\v1\controllers
 */
class UserController extends AbstractActiveController
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
                    'actions' => ['profile'],
                    'allow' => true,
                    'roles' => ['@'],
                    'verbs' => ['POST']
                ],
                [
                    'actions' => ['signup'],
                    'allow' => true,
                    'roles' => ['?'],
                    'verbs' => ['POST']
                ],
            ],
        ];

        // authMethods
        $behaviors['authenticator'] = self::getAuthMethods([], [
            [
                'class' => HttpBearerAuth::className(),
            ]
        ], ['help', 'signup']);

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
                'description' => "Get user info",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'BearerAuthenticated' => 'Authentication method based on HTTP Bearer token'
                ],
                'return' => [
                    'data' => [
                        Users::fieldTest()
                    ]
                ]
            ],
            'GET /'. $this->module->id .'/' . $this->id . '/{id}' => [
                'description' => "Get user info",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'BearerAuthenticated' => 'Authentication method based on HTTP Bearer token'
                ],
                'params' => [
                    'id' => 'integer'
                ],
                'return' => [
                    'data' => [
                        Users::fieldTest()
                    ]
                ]
            ],
            'POST /'. $this->module->id .'/' . $this->id . '/signup' => [
                'description' => "User sign up",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                ],
                'params' => [
                    'email' => 'string',
                    'username' => 'string'
                ],
                'return' => [
                    'data' => [
                        Users::fieldTest()
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        throw new MethodNotImplementedException();
    }

    /**
     * POST
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionSignup()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionProfile()
    {
        throw new MethodNotImplementedException();
    }
}