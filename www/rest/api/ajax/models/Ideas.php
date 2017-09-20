<?php

namespace rest\api\ajax\models;

/**
 * Class Ideas
 * @package rest\api\ajax\models
 */
final class Ideas extends \rest\models\Ideas
{
    /******************
     * TEST
     *****************/

    /**
     * @return array
     */
    public static function fieldTest()
    {
        return [
            'id' => 'integer',
            'username' => 'string'
        ];
    }

    /**
     * @return array
     */
    public static function fieldViewTest()
    {
        return [
            'id' => 'integer',
            'username' => 'string'
        ];
    }
}