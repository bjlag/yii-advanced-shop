<?php namespace core\tests\unit\entities\User;

use core\entities\User\User;

/**
 * Тестирование редактирования пользователя.
 */
class UserEditTest extends \Codeception\Test\Unit
{
    public function testSuccess()
    {
        $user = User::create(
            'test',
            'test@test.ru',
            'password'
        );

        sleep(2);

        $user->edit(
            $username ='new test',
            $email = 'new-test@test.ru',
            $status = User::STATUS_WAIT
        );

        expect('Должно установиться новое имя пользователя', $user->username)->equals($username);
        expect('Должно установиться новый емейл пользователя', $user->email)->equals($email);
        expect('Должно смениться статус пользователя', $user->status)->equals(User::STATUS_WAIT);
        expect('Дата обновления должна быть больше даты создания', $user->updated_at)->greaterThan($user->created_at);
    }
}