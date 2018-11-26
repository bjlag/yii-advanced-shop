<?php

namespace common\tests\unit\forms;

use common\fixtures\UserFixture;
use core\forms\auth\LoginForm;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function testValidationSuccess()
    {
        $model = new LoginForm([
            'username' => 'admin',
            'password' => 'password',
        ]);

        expect('Должны пройти валидацию', $model->validate())->true();
        expect('Поле rememberMe должно быть TRUE', $model->rememberMe)->true();
    }

    public function testRequiredFields()
    {
        $model = new LoginForm();
        $model->validate();

        expect('Должна быть ошибка, что не введен пароль', $model->errors)->hasKey('password');
        expect('Должна быть ошибка, что не введен логин', $model->errors)->hasKey('username');
    }
}
