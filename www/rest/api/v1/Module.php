<?php

namespace rest\api\v1;

/**
 * Class Module
 * @package rest\api\v1
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $id = 'v1';

    /**
     * @var string
     */
    public $controllerNamespace = 'rest\api\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
