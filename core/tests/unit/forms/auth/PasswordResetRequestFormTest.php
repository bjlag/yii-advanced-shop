<?php namespace core\tests\forms\auth;

use common\fixtures\UserFixture;
use core\forms\auth\PasswordResetRequestForm;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit
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

    public function testNotFoundUserWithSpecifiedEmail()
    {
        $form = new PasswordResetRequestForm(['email' => 'not_exist_email@mail.ru']);
        expect('Валидация не должна пройти, если не найден пользователь с указанным емейлом', $form->validate())->false();
    }

    public function testCorrectEmail()
    {
        $form = new PasswordResetRequestForm(['email' => 'nicole.paucek@schultz.info']);
        expect('При правильном емейле валидация должна пройти', $form->validate())->true();
    }

    public function testWrongEmail()
    {
        $form = new PasswordResetRequestForm(['email' => 'wrong_email@schultz']);
        expect('Не должны пройти валидацию, если передан неправильный емейл', $form->validate())->false();
    }

    public function testRequiredField()
    {
        $form = new PasswordResetRequestForm();
        expect('Не должны пройти валидацию, если не указан емейл', $form->validate())->false();
    }
}