<?php

namespace rest\api\v1\models;

/**
 * Class Users
 * @package rest\api\v1\models
 */
final class Users extends \rest\models\Users
{
    /******************
     * DEFAULT
     *****************/

    /**
     * @return array
     */
    public static function fieldDefault()
    {
        return [
            'id',
            'username'
        ];
    }

    /**
     * @return array
     */
    public static function extraFieldDefault()
    {
        return [];
    }

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