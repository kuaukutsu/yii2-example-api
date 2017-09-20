<?php

namespace rest\api\graphql;

/**
 * Class Module
 * @package rest\api\graphql
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $id = 'graphql';

    /**
     * @var string
     */
    public $controllerNamespace = 'rest\api\graphql\controllers';

    /**
     * @var string
     */
    public $defaultRoute = 'default';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
