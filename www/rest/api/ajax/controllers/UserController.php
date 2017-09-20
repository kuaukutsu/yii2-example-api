<?php

namespace rest\api\ajax\controllers;

use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use rest\components\exceptions\MethodNotImplementedException;
use rest\api\ajax\components\AuthToken;

/**
 * Class UserController
 * @package rest\api\ajax\controllers
 */
class UserController extends AbstractAjaxController
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
                'description' => "API Help: User. GET /" . $this->module->id . "/" . $this->id . "/help",
            ],
            'GET /' . $this->module->id . '/' . $this->id => [
                'description' => "User list",
                'authorization' => [
                    'KeyAuthenticated' => 'Authentication using the private key',
                    'Authorization token type' => 'Bearer token'
                ],
                'return' => [
                    'data' => []
                ]
            ],
            'GET /' . $this->module->id . '/' . $this->id . '/{id}' => [
                'description' => "User view",
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
}