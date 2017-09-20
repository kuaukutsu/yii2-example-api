<?php

namespace rest\api\ajax;

/**
 * Class Module
 * @package rest\api\v1
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $id = 'ajax';

    /**
     * @var string
     */
    public $controllerNamespace = 'rest\api\ajax\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
