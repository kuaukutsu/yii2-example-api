<?php

namespace rest\api\v1\controllers;

use yii\filters\Cors;
use yii\web\ForbiddenHttpException;
use rest\components\ActiveController;
use rest\components\AuthHeader;
use rest\api\v1\models\Users;

/**
 * Class AbstractController
 * @package rest\api\v1\controllers
 */
abstract class AbstractActiveController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Headers' => ['APIKey', 'Accept', 'Authorization', 'X-Request-With', 'X-Requested-With', 'Link'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Total-Count',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Current-Page',
                    'X-Pagination-Per-Page',
                    'Link'
                ],
            ],
        ];

        // QueryParamAuth
        $behaviors['authenticator'] = [];

        return $behaviors;
    }

    /**
     * @var Users
     */
    private $_user;

    /**
     * @return Users
     * @throws ForbiddenHttpException
     */
    protected function getUserIdentity()
    {
        if ($this->_user === null) {
            if (($this->_user = Users::findIdentity(\Yii::$app->getUser()->getId())) === null) {
                throw new ForbiddenHttpException();
            }
        }

        return $this->_user;
    }

    /**
     * @param array $require
     * @param array $auth
     * @param array $optional
     * @return array
     */
    protected static function getAuthMethods(array $require = [], array $auth = [], array $optional = [])
    {
        /** @var array $require Require for Mobile */
        $require = array_merge($require, [
            [
                'class' => AuthHeader::className(),
                'header' => 'api-header',
                'apikey' => 'api-key'
            ]
        ]);

        return parent::getAuthMethods($require, $auth, $optional);
    }
}