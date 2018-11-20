<?php

namespace core\services\manage;

use core\entities\User\User;
use core\forms\manage\User\CreateUserForm;
use core\forms\manage\User\UpdateUserForm;
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

    /**
     * @param int $id
     * @param UpdateUserForm $form
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(int $id, UpdateUserForm $form): void
    {
        $user = $this->repository->byId($id);
        $user->edit($form->username, $form->email, $form->status);

        $this->repository->save($user);
    }
}