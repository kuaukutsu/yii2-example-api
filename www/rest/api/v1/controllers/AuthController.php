<?php

namespace rest\api\v1\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\web\ForbiddenHttpException;
use rest\models\Users;

/**
 * Class AuthController
 * @package rest\api\v1\controllers
 */
class AuthController extends AbstractActiveController
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
                    'actions' => ['index', 'help'],
                    'allow' => true,
                    'verbs' => ['GET']
                ],
            ],
        ];

        // authMethods
        $behaviors['authenticator'] = self::getAuthMethods([], [
            [
                'class' => HttpBasicAuth::className(),
                'auth'  => function ($username, $password) {
                    $user = Users::findByUsername($username);
                    return ($user !== null && $user->validatePassword($password)) ? $user : null;
                },
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
                'description' => "REST API Help. GET /". $this->module->id ."/" . $this->id,
            ],
            'GET /'. $this->module->id .'/' . $this->id => [
                'description' => "Get token",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'HttpAuthenticated' => 'HTTP Basic authentication method'
                ],
                'return' => [
                    'data' => [
                        'token' => 'string'
                    ]
                ]
            ],
        ];
    }

    /**
     * GET
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        return ['token' => $this->getUserIdentity()->getAuthKey()];
    }
}