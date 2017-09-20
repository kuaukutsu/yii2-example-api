<?php

namespace rest\api\ajax\controllers;

use rest\components\exceptions\MethodNotImplementedException;
use rest\api\ajax\components\AuthToken;

/**
 * Class IdeaController
 * @package rest\api\ajax\controllers
 */
class IdeaController extends AbstractAjaxController
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
                    'verbs' => ['GET']
                ],
                [
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['@'],
                    'verbs' => ['GET']
                ],
            ],
        ];

        // authMethods
        $behaviors['authenticator'] = self::getAuthMethods([
            [
                'class' => AuthToken::className(),
            ]
        ]);

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
                'description' => "API Help: User. GET /" . $this->module->id . "/" . $this->id . "/help",
            ],
            'GET /' . $this->module->id . '/' . $this->id => [
                'description' => "Idea list",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'Authorization token type' => 'Bearer token'
                ],
                'return' => [
                    'data' => []
                ]
            ],
            'GET /' . $this->module->id . '/' . $this->id . '/{id}' => [
                'description' => "Idea view",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'Authorization token type' => 'Bearer token'
                ],
                'return' => [
                    'data' => []
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
}