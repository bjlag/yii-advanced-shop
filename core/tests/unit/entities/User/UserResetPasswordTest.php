<?php namespace core\tests\entities\User;

use core\entities\User\User;

/**
 * Тестирование сброса пароля пользователя.
 */
class UserResetPasswordTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;
    /** @var User */
    private $user;
    
    protected function _before()
    {
        $this->user = User::create(
            'test',
            'test@test.ru',
            'password'
        );
    }

    protected function _after()
    {
    }

    /**
     * Запрос на сброс пароля.
     */
    public function testRequestPasswordReset()
    {
        expect($this->user->password_reset_token)->isEmpty();

        $this->user->requestPasswordResetToken();

        expect('Должен установиться токен для сброса пароля', $this->user->password_reset_token)->notEmpty();

        $this->tester->expectException(\DomainException::class, function () {
            $this->user->requestPasswordResetToken();
        });
    }

    public function testResetPassword()
    {
        $oldHash = $this->user->password_hash;
        $this->user->requestPasswordResetToken();
        $this->user->resetPassword('new_password');

        expect('Хеш пароля должен обновиться}', $this->user->password_hash)->notEquals($oldHash);
        expect('Должен удалиться токен для сброса пароля', $this->user->password_reset_token)->isEmpty();
    }
}