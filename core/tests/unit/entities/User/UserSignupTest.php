<?php

namespace core\tests\unit\entities\User;

use core\entities\User\User;

/**
 * Тестирование регистрации пользователя.
 */
class UserSignupTest extends \Codeception\Test\Unit
{
    private $username;
    private $email;
    private $password;
    /** @var User */
    private $user;

    protected function _before()
    {
        $this->user = User::requestSignup(
            $this->username ='test',
            $this->email = 'test@test.ru',
            $this->password ='password'
        );
    }

    public function testUserNameSet()
    {
        expect('У модели должен установиться username', $this->username)->equals($this->user->username);
    }

    public function testEmailSet()
    {
        expect('У модели должен установиться email', $this->email)->equals($this->user->email);
        expect('email_confirm_token не должен быть пустым', $this->user->email_confirm_token)->notEmpty();
    }

    public function testPasswordSet()
    {
        expect('Пароль не должен совпадать с хешем', $this->password)->notEquals($this->user->password_hash);
        expect('password_hash не должен быть пустым', $this->user->password_hash)->notEmpty();
    }

    public function testAuthKeySet()
    {
        expect('auth_key не должен быть пустым', $this->user->auth_key)->notEmpty();
    }

    public function testDatesCreatedAndUpdatedSet()
    {
        expect('Должна установиться дата создания', $this->user->created_at)->notEmpty();
        expect('Должна установиться дата обновления', $this->user->updated_at)->notEmpty();
        expect('Дата создания должна быть ровна дате обновления', $this->user->created_at)->equals($this->user->updated_at);
    }

    public function testStatusSet()
    {
        expect('Статус пользователя должен быть WAIT', $this->user->isWait())->true();
    }

    /**
     * Подтверждение регистрации и активация пользователя.
     */
    public function testConfirmSuccess()
    {
        $this->user->confirmSignup();

        expect('Статус пользователя должен быть ACTIVE', $this->user->isActive())->true();
        expect('Токен на подтверждение емейла должен сброситься', $this->user->email_confirm_token)->isEmpty();
    }

    /**
     * Исключение в случае подтверждения уже активного пользователя.
     */
    public function testConfirmActiveUser()
    {
        $this->expectException(\DomainException::class);

        $this->user->status = User::STATUS_ACTIVE;
        $this->user->confirmSignup();
    }
}
