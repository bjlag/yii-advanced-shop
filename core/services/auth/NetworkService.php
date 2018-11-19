<?php

namespace core\services\auth;

use core\entities\User\Network;
use core\entities\User\User;
use core\repositories\UserRepository;

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
        $user = $this->users->findByNetwork($network, $identity);
        if ($user) {
            return $user;
        }

        $user = User::networkSignup();
        $user->networks = Network::create($network, $identity);
        $this->users->save($user);

        return $user;
    }
}