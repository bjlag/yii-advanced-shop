<?php namespace core\tests\forms\auth;

use core\forms\auth\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    public function testValidated()
    {
        $form = new ResetPasswordForm();
        expect($form->validate())->false();

        $form = new ResetPasswordForm(['password' => '12345']);
        expect($form->validate())->false();

        $form = new ResetPasswordForm(['password' => '123456']);
        expect($form->validate())->true();
    }
}