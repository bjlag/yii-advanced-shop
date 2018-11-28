<?php namespace core\tests\services\auth;

use common\fixtures\UserFixture;
use core\forms\auth\LoginForm;
use core\services\auth\LoginService;

class LoginServicesTest extends \Codeception\Test\Unit
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

    public function testSuccess()
    {
        $form = new LoginForm(['username' => 'bayer.hudson', 'password' => 'password_0']);

        $service = new LoginService();
        expect($service->login($form))->true();
    }

    public function testWrongPassword()
    {
        $form = new LoginForm(['username' => 'bayer.hudson', 'password' => 'wrong_password']);

        $service = new LoginService();
        expect($service->login($form))->false();
    }

    public function testWrongUsername()
    {
        $form = new LoginForm(['username' => 'wrong_username', 'password' => 'password_0']);

        $service = new LoginService();
        expect($service->login($form))->false();
    }
}