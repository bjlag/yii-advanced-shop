<?php

namespace core\services\auth;

use core\entities\User\User;
use core\forms\auth\PasswordResetRequestForm;
use core\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;

class PasswordResetService
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

    /**
     * Отправить письмо на сброс пароля.
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

        $send = $this->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]
            )
            ->setFrom($this->emailFrom)
            ->setTo($form->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$send) {
            throw new \RuntimeException('Ошибка при отправке письма для восстановления пароля.');
        }
    }

    /**
     * Валидация токена.
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
     * Сброс пароля.
     * @param User $user
     * @param string $password
     */
    public function reset(User $user, string $password): void
    {
        $user->resetPassword($password);
        $this->users->save($user);
    }
}