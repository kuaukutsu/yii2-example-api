<?php

namespace rest\api\ajax\components;

use Yii;
use yii\base\ActionFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\ServerErrorHttpException;

/**
 * Class IpFilter
 * @package rest\api\ajax\components
 */
class IpFilter extends ActionFilter
{
    /**
     * @var Request the current request. If not set, the `request` application component will be used.
     */
    public $request;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->request = $this->request ?: Yii::$app->getRequest();

        // check SERVER ADDR
        if (! isset($_SERVER['SERVER_ADDR']) || empty($_SERVER['SERVER_ADDR'])) {
            throw new ServerErrorHttpException('Server empty');
        }

        // check IP
        $host = ArrayHelper::getValue($_SERVER, 'HTTP_ORIGIN', $this->request->getReferrer());
        if (! self::inListWhite(gethostbyname(parse_url($host, PHP_URL_HOST)))) {
            throw new ForbiddenHttpException('Access denied');
        }

        return true;
    }

    /**
     * @param string $ip
     * @return bool
     */
    private static function inListWhite($ip)
    {
        return $_SERVER['SERVER_ADDR'] === $ip;
    }
}
