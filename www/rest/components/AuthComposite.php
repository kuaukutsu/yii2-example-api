<?php

namespace rest\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\AuthInterface;
use yii\filters\auth\CompositeAuth;
use yii\web\UnauthorizedHttpException;

/**
 * Class AuthComposite
 * @package rest\api\v1\components
 */
class AuthComposite extends CompositeAuth
{
    /**
     * @var array the supported authentication methods. This property should take a list of supported
     * authentication methods, each represented by an authentication class or configuration.
     */
    public $authRequire = [];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        /**
         * Если методы не объявлены, то копируем на их место обязательные,
         * а что бы их не выполнять дважды - обнуляем.
         */
        if (empty($this->authMethods)) {
            $this->authMethods = $this->authRequire;
            $this->authRequire = [];

            // обязательные должны выполняться обязательно
            $this->optional = [];
        }

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        // Должны обязательно закончится успехом
        foreach ($this->authRequire as $i => $auth) {
            if (!$auth instanceof AuthInterface) {
                $this->authRequire[$i] = $auth = Yii::createObject($auth);
                if (!$auth instanceof AuthInterface) {
                    throw new InvalidConfigException(get_class($auth) . ' must implement yii\filters\auth\AuthInterface');
                }
            }

            try {
                if (! $auth->authenticate($user, $request, $response)) {
                    $this->optional = [];

                    return null;
                }
            }  catch (UnauthorizedHttpException $e) {
                $this->optional = [];

                throw $e;
            }
        }

        return parent::authenticate($user, $request, $response);
    }
}