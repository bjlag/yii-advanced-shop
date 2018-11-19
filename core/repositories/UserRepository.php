<?php

namespace core\repositories;

use core\entities\User\User;

class UserRepository
{
    /**
     * Сохранить модель пользователя.
     * @param User $user
     */
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Ошибка при сохранении пользователя.');
        }
    }

    /**
     * Поиск пользователя по социальной сети.
     * @param string $network
     * @param string $identity
     * @return User
     */
    public function findByNetwork(string $network, string $identity): ?User
    {
        return User::find()->innerJoinWith(User::RELATION_NETWORKS . ' n')
            ->where(['n.network' => $network, 'n.identity' => $identity])->one();
    }
}