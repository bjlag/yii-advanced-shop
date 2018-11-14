<?php

namespace core\forms\auth;

use core\entities\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\core\entities\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с указанным емейлом не найден'
            ],
        ];
    }
}
