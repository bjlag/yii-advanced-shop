<?php

namespace core\services\auth;

use core\entities\User;
use core\forms\auth\LoginForm;
use Yii;

class LoginService
{
    /**
     * Logs in a user using the provided username and password.
     *
     * @param LoginForm $form
     * @return bool whether the user is logged in successfully
     */
    public function login(LoginForm $form): bool
    {
        $user = $this->getUser($form->username);
        if (!$user || !$this->validatePassword($form, $user)) {
            return false;
        }

        return Yii::$app->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * Finds user by [[username]]
     *
     * @param string $username
     * @return User|null
     */
    private function getUser(string $username): ?User
    {
        return User::findByUsername($username);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param LoginForm $form
     * @param User $user
     * @return bool
     */
    private function validatePassword(LoginForm $form, ?User $user): bool
    {
        return $user->validatePassword($form->password);
    }
}