<?php

namespace common\repositories;

use common\entities\User;

class UserRepository
{
    /**
     * Сохранить модель пользователя.
     * @param User $user
     */
    public function save(User $user)
    {
        if (!$user->save()) {
            throw new \RuntimeException('Ошибка при сохранении пользователя.');
        }
    }
}