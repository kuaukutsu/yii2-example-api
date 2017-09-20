<?php

namespace rest\components;

use yii\helpers\ArrayHelper;

/**
 * Class Response
 * @package app\components\web
 */
class Response extends \yii\web\Response
{
    const FORMAT_JSONR = 'jsonr';
    const FORMAT_JSONVP = 'jsonvp';

    /********************
     * HELPERS
     *******************/

    /**
     * @param array|string $errros
     * @return array
     */
    public static function parseErrors($errros)
    {
        if (is_array($errros)) {

            if (ArrayHelper::isAssociative($errros)) {
                $result = [];
                foreach (array_keys($errros) as $key) {
                    $result[] = [
                        'name' => $key,
                        'message' => (is_array($errros[$key])) ? implode(',', $errros[$key]) : $errros[$key]
                    ];
                }

                return $result;
            }

            return $errros;

        } else {

            return [
                'name' => 'unknown',
                'message' => $errros
            ];
        }
    }
}