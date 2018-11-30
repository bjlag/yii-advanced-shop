<?php

namespace core\services\manage\shop;

use core\entities\shop\Tags;
use core\forms\manage\shop\TagForm;
use core\repositories\TagsRepository;

/**
 * Class TagsManageService
 * @package core\services\manage\shop
 */
class TagsManageService
{
    private $repository;

    /**
     * TagsManageService constructor.
     * @param TagsRepository $repository
     */
    public function __construct(TagsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Создание метки.
     * @param TagForm $form
     * @return Tags
     */
    public function create(TagForm $form): Tags
    {
        $tag = new Tags([
            'name' => $form->name,
            'slug' => $form->slug
        ]);

        $this->repository->save($tag);

        return $tag;
    }

    /**
     * Редактирование метки.
     * @param int $id
     * @param TagForm $form
     */
    public function edit(int $id, TagForm $form): void
    {
        $tag = $this->repository->byId($id);
        $tag->edit($form->name, $form->slug);

        $this->repository->save($tag);
    }

    /**
     * @param int $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(int $id): void
    {
        $tag = $this->repository->byId($id);
        $this->repository->remove($tag);
    }
}