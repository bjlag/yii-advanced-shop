<?php

namespace core\repositories;

use core\entities\User\User;
use yii\web\NotFoundHttpException;

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
     * @param int $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function byId(int $id): User
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пользователь не найден');
    }

    /**
     * Поиск пользователя по адресу электронной почты.
     * @param string $email
     * @return User
     */
    public function byEmail(string $email): User
    {
        if ($model = User::findOne(['email' => $email])) {
            return $model;
        }

        throw new \DomainException("Пользователь с емейлом {$email} не найден!");
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