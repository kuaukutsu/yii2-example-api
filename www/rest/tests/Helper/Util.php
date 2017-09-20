<?php

namespace rest\tests\Helper;

use yii\helpers\ArrayHelper;

/**
 * Class Util
 * @package rest\tests\Helper
 */
class Util
{
    /**
     * @param string $file
     * @return string
     */
    public static function getUrlCept($file)
    {
        if (preg_match('#(?<model>\w{1,})(?<version>(V\d{1,}|Ajax))Cept#i', basename($file), $match)) {
            return strtolower(sprintf('/%s/%s',
                ArrayHelper::getValue($match, 'version'), ArrayHelper::getValue($match, 'model')));
        }

        return '';
    }
}