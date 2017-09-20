<?php

namespace rest\api\ajax\controllers;

use yii\filters\Cors;
use yii\web\ForbiddenHttpException;
use rest\components\ActiveController;
use rest\api\ajax\components\HostFilter;
use rest\api\ajax\components\IpFilter;
use rest\api\ajax\models\Users;

/**
 * Class AbstractAjaxController
 * @package rest\api\v1\controllers
 */
abstract class AbstractAjaxController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Access-Control-Allow-Origin
        $originAllow = self::getOrigin();

        // CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => $originAllow,
                'Access-Control-Request-Headers' => ['Accept','Authorization','X-Request-With','X-Requested-With','Link'],
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

        // disable in LAN
        if (self::isLocalAreaNetwork($_SERVER['SERVER_ADDR']) === false) {

            // Host filter
            $behaviors['hostFilter'] = [
                'class' => HostFilter::className(),
                'hosts' => $originAllow
            ];

            // IP filter
            $behaviors['ipFilter'] = [
                'class' => IpFilter::className()
            ];
        }

        // Auth
        $behaviors['authenticator'] = self::getAuthMethods();

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

    /*****************
     * HELPERS
     ****************/

    /**
     * @param string $ip
     * @return bool
     */
    protected static function isLocalAreaNetwork($ip)
    {
        return (strpos($ip, '10.') === 0
            || strpos($ip, '127.') === 0
            || strpos($ip, '172.16.') === 0
            || strpos($ip, '192.168.') === 0);
    }

    /**
     * @return array
     */
    protected static function getOrigin()
    {
        $example = [
            'rest' => 'https://rest.localhost',
            'back' => 'https://backend.localhost',
            'front' => 'https://localhost',
        ];

        $origin = [];
        foreach ($example as $key => $domen) {
            if ($key !== 'rest') {
                $origin[] = rtrim($domen, '/');
            }
        }

        return $origin;
    }
}