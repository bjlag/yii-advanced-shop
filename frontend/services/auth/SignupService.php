<?php

namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;
use yii\mail\MailerInterface;

class SignupService
{
    private $emailFrom;
    private $mailer;

    public function __construct(array $emailFrom, MailerInterface $mailer)
    {
        $this->emailFrom = $emailFrom;
        $this->mailer = $mailer;
    }

    public function request(SignupForm $form): User
    {
        $user = User::requestSingup(
            $form->username,
            $form->email,
            $form->password
        );

        if (!$user->save()) {
            throw new \RuntimeException('Ошибка при сохранении пользователя');
        }

        $send = $this->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user' => $user]
            )
            ->setFrom($this->emailFrom)
            ->setTo($form->email)
            ->setSubject('Подтвердите адрес электронной почты')
            ->send();

        if (!$send) {
            throw new \RuntimeException('Не удалось отправить письмо для поддтверждения емейла.');
        }

        return $user;
    }

    public function confirm(string $token): User
    {
        $user = User::findByEmailConfirmToken($token);
        if (!$user) {
            throw new \DomainException('Пользователь с указанным токеном не найден');
        }

        $user->confirmSignup();

        if (!$user->save()) {
            throw new \RuntimeException('Ошибка при сохранении пользователя');
        }

        return $user;
    }
}