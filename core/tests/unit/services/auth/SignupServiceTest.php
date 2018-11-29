<?php namespace core\tests\services\auth;

use common\fixtures\UserFixture;
use core\entities\User\User;
use core\forms\auth\SignupForm;
use core\repositories\UserRepository;
use core\services\auth\SignupService;
use yii\mail\MessageInterface;

class SignupServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;

    /** @var SignupService $service */
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
        $this->service = new SignupService(
            $repository,
            [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'],
            \Yii::$app->mailer
        );
    }

    public function testRequestSuccess()
    {
        $form = new SignupForm([
            'username' => $username = 'username',
            'email' => $email = 'email@email.ru',
            'password' => $password = 'password'
        ]);

        /** @var User $user */
        $user = $this->service->request($form);

        $this->tester->seeEmailIsSent();

        /** @var MessageInterface $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();
        expect('Должно отправить письмо', $emailMessage)->isInstanceOf(MessageInterface::class);
        expect($emailMessage->getTo())->hasKey($form->email);

        expect($user->username)->equals($username);
        expect($user->email)->equals($email);
        expect(\Yii::$app->getSecurity()->validatePassword($password, $user->password_hash))->true();
    }

    public function testConfirmSuccess()
    {
        $user = $this->service->confirm('ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1S');

        expect('Токен должен удалиться', $user->email_confirm_token)->isEmpty();
    }
}