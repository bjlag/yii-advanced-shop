<?php

namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\PasswordResetRequestForm;
use Yii;

class PasswordResetService
{
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @param PasswordResetRequestForm $form
     * @return void whether the email was send
     * @throws \yii\base\Exception
     */
    public function request(PasswordResetRequestForm $form): void
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $form->email,
        ]);

        if (!$user) {
            throw new \DomainException("Пользователь с емейлом {$form->email} не найден!");
        }

        if (!$user->requestPasswordResetToken()) {
            throw new \DomainException("На емейл {$form->email} уже было отправлено письмо на восстановление пароля! 
                Проверьте почту. <br>Запрос можно делать не чаще одно раза в час.");
        }

        $send = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($form->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
        
        if (!$send) {
            throw new \RuntimeException('Извините, нам не удалось отправить письмо на указанный адрес для восстановления 
                пароля. Попробуйте еще раз.');
        }
    }

    /**
     * @param string $token
     * @return User
     */
    public function validateToken(string $token): User
    {
        if (empty($token)) {
            throw new \DomainException('Не передан токен для сброса пароля.');
        }

        $user = User::findByPasswordResetToken($token);
        if (!$user) {
            throw new \DomainException('Запрос на восстановление пароля не найден.');
        }

        return $user;
    }

    /**
     * @param User $user
     * @param string $password
     */
    public function reset(User $user, string $password): void
    {
        $user->setPassword($password);
        $user->removePasswordResetToken();

        if(!$user->save(false)) {
            throw new \RuntimeException('Не удалось сохранить новый пароль. Попробуйте еще раз.');
        }
    }
}