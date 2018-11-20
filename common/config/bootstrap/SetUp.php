<?php

namespace common\config\bootstrap;

use core\repositories\UserRepository;
use core\services\auth\LoginService;
use core\services\auth\NetworkService;
use core\services\auth\PasswordResetService;
use core\services\auth\SignupService;
use core\services\ContactService;
use core\services\manage\UserManageService;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(UserRepository::class, function () {
            return new UserRepository();
        });

        $container->setSingleton(LoginService::class, function () {
            return new LoginService();
        });

        $container->setSingleton(ContactService::class, [], [
            Yii::$app->params['adminEmail'],
            Instance::of(MailerInterface::class)
        ]);

        $container->setSingleton(SignupService::class, [], [
            Instance::of(UserRepository::class),
            [Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'],
            Instance::of(MailerInterface::class)
        ]);

        $container->setSingleton(PasswordResetService::class, [], [
            Instance::of(UserRepository::class),
            [Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'],
            Instance::of(MailerInterface::class)
        ]);

        $container->setSingleton(NetworkService::class, [], [
            Instance::of(UserRepository::class)
        ]);

        $container->setSingleton(UserManageService::class, [], [
            Instance::of(UserRepository::class)
        ]);
    }
}