<?php

namespace rest\models;

use app\models\User;

/**
 * Class User
 * @package rest\models
 */
class Users extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'username'
        ];
    }
}