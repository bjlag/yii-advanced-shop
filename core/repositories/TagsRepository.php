<?php

namespace core\repositories;

use core\entities\shop\Tags;
use yii\web\NotFoundHttpException;

class TagsRepository
{
    /**
     * Сохранить модель метки.
     * @param Tags $tag
     */
    public function save(Tags $tag): void
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Ошибка при сохранении метки.');
        }
    }

    /**
     * @param Tags $tag
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Tags $tag): void
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Ошибка при удалении метки.');
        }
    }

    /**
     * Найти метку по ее ID.
     * @param int $id
     * @return Tags
     * @throws NotFoundHttpException
     */
    public function byId(int $id): Tags
    {
        if ($tag = Tags::findOne($id)) {
            return $tag;
        }

        throw new NotFoundHttpException('Метка не найдена.');
    }
}