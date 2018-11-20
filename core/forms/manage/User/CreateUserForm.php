<?php

namespace core\forms\manage\User;

use core\entities\User\User;
use yii\base\Model;

/**
 * Class CreateUserForm
 * @package core\forms\manage\User
 */
class CreateUserForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            [['email', 'username'], 'unique', 'targetClass' => User::class],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль'
        ];
    }
}