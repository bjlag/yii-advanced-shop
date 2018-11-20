<?php

namespace core\services\manage;

use core\entities\User\User;
use core\forms\manage\User\CreateUserForm;
use core\repositories\UserRepository;

/**
 * Class UserManageService
 * @package core\services\manage
 */
class UserManageService
{
    private $repository;

    /**
     * UserManageService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @param CreateUserForm $form
     * @return User
     */
    public function create(CreateUserForm $form): User
    {
        $user = User::create($form->username, $form->email, $form->password);
        $this->repository->save($user);

        return $user;
    }
}