<?php

namespace core\services\auth;

use core\entities\User\User;
use core\repositories\UserRepository;
use yii\web\IdentityInterface;

/**
 * Class NetworkService
 * @package core\services\auth
 */
class NetworkService
{
    private $users;

    /**
     * NetworkService constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Авторизация пользователя через социальную сеть.
     * @param string $network
     * @param string $identity
     * @return User
     */
    public function auth(string $network, string $identity): User
    {
        if ($user = $this->users->findByNetwork($network, $identity)) {
            return $user;
        }

        $user = User::networkSignup($network, $identity);
        $this->users->save($user);

        return $user;
    }

    /**
     * Добавить социальную сеть переданному пользователю.
     * @param User|IdentityInterface $user
     * @param string $network
     * @param string $identity
     */
    public function attach(User $user, string $network, string $identity): void
    {
        if ($this->users->findByNetwork($network, $identity)) {
            throw new \DomainException('Социальная сеть уже используется');
        }

        $user->attachNetwork($network, $identity);
        $this->users->save($user);
    }
}