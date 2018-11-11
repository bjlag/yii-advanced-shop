<?php

namespace common\tests\unit\forms\User;

use common\entities\User;

/**
 * Login form test
 */
class SignupTest extends \Codeception\Test\Unit
{
    public function testSuccess()
    {
        $user = User::singup(
            $username ='test',
            $email = 'test@test.ru',
            $password ='password'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->updated_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertNotEmpty($user->password_hash);
        $this->assertTrue($user->isActive());
    }
}
