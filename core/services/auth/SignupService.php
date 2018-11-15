<?php

namespace core\services\auth;

use core\entities\User;
use core\forms\auth\SignupForm;
use core\repositories\UserRepository;
use yii\mail\MailerInterface;

class SignupService
{
    private $users;
    private $emailFrom;
    private $mailer;

    public function __construct(UserRepository $users, array $emailFrom, MailerInterface $mailer)
    {
        $this->users = $users;
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

        $this->users->save($user);

        $send = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
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
        $this->users->save($user);

        return $user;
    }
}