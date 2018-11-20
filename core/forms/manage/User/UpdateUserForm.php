<?php

namespace core\forms\manage\User;

use core\entities\User\User;
use yii\base\Model;

/**
 * Class UpdateUserForm
 * @package core\forms\manage\User
 */
class UpdateUserForm extends Model
{
    public $username;
    public $email;
    public $status;

    private $_user;

    /**
     * UpdateUserForm constructor.
     * @param User $user
     * @param array $config
     */
    public function __construct(User $user, array $config = [])
    {
        parent::__construct($config);

        $this->username = $user->username;
        $this->email = $user->email;
        $this->status = $user->status;

        $this->_user = $user;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'status'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            [['email', 'username'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_WAIT]],
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
            'status' => 'Статус'
        ];
    }
}