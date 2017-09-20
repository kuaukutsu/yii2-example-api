<?php

namespace rest\api\ajax\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

/**
 * Class HostFilter
 * @package rest\api\ajax\components
 */
class HostFilter extends ActionFilter
{
    /**
     * @var Request the current request. If not set, the `request` application component will be used.
     */
    public $request;

    /**
     * @var array the permitted host
     */
    public $hosts = [];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->request = $this->request ?: Yii::$app->getRequest();

        // check Hosts
        $referrer = sprintf('%s://%s',
            parse_url($this->request->getReferrer(), PHP_URL_SCHEME),
            parse_url($this->request->getReferrer(), PHP_URL_HOST)
        );

        if ($referrer === null || array_search($referrer, $this->hosts) === false) {
            throw new ForbiddenHttpException('Hosts denied');
        }

        return true;
    }
}
