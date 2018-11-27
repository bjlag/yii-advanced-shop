<?php namespace core\tests\forms\auth;

use common\fixtures\UserFixture;
use core\forms\auth\SignupForm;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testValidated()
    {
        $form = new SignupForm([
            'username' => 'username',
            'email' => 'email@email.ru',
            'password' => 'password',
        ]);

        expect($form->validate())->true();
    }

    public function testSignupExistUser()
    {
        $form = new SignupForm([
            'username' => 'bayer.hudson',
            'email' => 'nicole.paucek@schultz.info',
            'password' => 'password',
        ]);

        expect($form->validate())->false();
        expect($form->errors)->hasKey('username');
        expect($form->errors)->hasKey('email');
    }

    public function testValidatedPassword()
    {
        $form = new SignupForm([
            'username' => 'username',
            'email' => 'email@email.ru',
            'password' => '12345',
        ]);

        expect($form->validate())->false();
        expect($form->errors)->hasKey('password');
    }
}