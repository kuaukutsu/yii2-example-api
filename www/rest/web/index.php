<?php
defined('YII_DEBUG') or define('YII_DEBUG', ($_SERVER['SERVER'] === 'dev'));
defined('YII_ENV') or define('YII_ENV', $_SERVER['SERVER']);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/main.php'),
    [] // local config
);

(new yii\web\Application($config))->run();
