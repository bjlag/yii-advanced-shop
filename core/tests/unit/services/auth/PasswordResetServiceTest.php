<?php

namespace core\tests\services\auth;

use common\fixtures\UserFixture;
use core\entities\User\User;
use core\forms\auth\PasswordResetRequestForm;
use core\repositories\UserRepository;
use core\services\auth\PasswordResetService;
use yii\mail\MessageInterface;

class PasswordResetServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;

    /** @var PasswordResetService */
    private $service;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);

        $repository = $this->getMockBuilder(UserRepository::class)->setMethods(['save'])->getMock();
        $repository->method('save')->willReturn(true);

        /** @var UserRepository $repository */
        $this->service = new PasswordResetService(
            $repository,
            [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'],
            \Yii::$app->mailer
        );
    }

    public function testRequestSuccess()
    {
        $form = new PasswordResetRequestForm(['email' => 'nicole.paucek@schultz.info']);
        $this->service->request($form);

        $this->tester->seeEmailIsSent();

        /** @var MessageInterface $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();
        expect('Должно отправить письмо', $emailMessage)->isInstanceOf(MessageInterface::class);
        expect($emailMessage->getFrom())->hasKey(\Yii::$app->params['supportEmail']);
        expect($emailMessage->getTo())->hasKey($form->email);
        expect($emailMessage->getSubject())->equals('Password reset for ' . \Yii::$app->name);
    }

    public function testNotFoundEmail()
    {
        $form = new PasswordResetRequestForm(['email' => 'wrong@email.ru']);

        $this->tester->expectException(\DomainException::class, function () use ($form) {
            $this->service->request($form);
        });
    }

    public function testNotActiveUser()
    {
        $form = new PasswordResetRequestForm(['email' => 'nicole.paucek2@schultz.info']);

        $this->tester->expectException(\DomainException::class, function () use ($form) {
            $this->service->request($form);
        });
    }

    public function testValidateWrongToken()
    {
        // Истек срок давности текена
        $this->tester->expectException(\DomainException::class, function () {
            $this->service->validateToken('ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317');
        });

        // Пользователя с таким токеном нет
        $this->tester->expectException(\DomainException::class, function () {
            $this->service->validateToken('ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_' . time());
        });
    }

    public function testResetPassword()
    {
        /** @var User $user */
        $user = $this->tester->grabFixture('user', 'active');
        $oldPasswordHash = $user->password_hash;

        expect('В фикстуре должен быть хеш пароля', $oldPasswordHash)->notEmpty();

        $this->service->reset($user, 'new_password');

        expect('Стрый хеш не должен быть равен новому', $oldPasswordHash)->notEquals($user->password_hash);
        expect('Должна пройти валидация нового пароля', \Yii::$app->security
            ->validatePassword('new_password', $user->password_hash))->true();
    }
}