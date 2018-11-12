<?php

namespace common\config\bootstrap;

use frontend\services\auth\PasswordResetService;
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

        $container->setSingleton(PasswordResetService::class, [], [
            [Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'],
            Instance::of(MailerInterface::class)
        ]);
    }
}