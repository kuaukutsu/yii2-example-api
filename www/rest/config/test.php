<?php

$main = yii\helpers\ArrayHelper::merge(
    require('main.php'),
    [] // local config
);

// current
$config = [
    'id' => 'example-api-tests',
    'name' => 'Example API',
    'language' => 'ru',
    'basePath' => dirname(dirname(__DIR__)),

    'components' => $main['components'],

    'params' => yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/../../config/params.php'),
        require(__DIR__ . '/params.php')
    ),
];

return $config;