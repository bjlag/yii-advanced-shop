<?php

namespace core\tests\unit\forms\manage\shop;

use common\fixtures\TagsFixture;
use core\entities\shop\Tags;
use core\forms\manage\shop\TagForm;

/**
 * Login form test
 */
class TagFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'tags' => [
                'class' => TagsFixture::class,
                'dataFile' => codecept_data_dir() . 'tags.php'
            ]
        ];
    }

    public function testTagCreate()
    {
        $form = new TagForm(null, [
            'name' => $name = 'new_tag',
            'slug' => $slug = 'new_slug'
        ]);

        expect('Должны пройти валидацию', $form->validate())->true();
        expect('Должен установиться атрибут name', $form->name)->equals($name);
        expect('Должен установиться атрибут slug', $form->slug)->equals($slug);
    }

    public function testRequiredFields()
    {
        $form = new TagForm();

        expect('Должны пройти валидацию', $form->validate())->false();
        expect('Имя обязательное поле', $form->getErrors())->hasKey('name');
        expect('Слаг обязательное поле', $form->getErrors())->hasKey('slug');
    }

    public function testSlugPattern()
    {
        $form = new TagForm(null, [
            'name' => 'new_tag',
            'slug' => 'new*slug'
        ]);

        expect('Не должны пройти валидацию', $form->validate())->false();
        expect('Слаг не удовлетворяет reg паттерну', $form->getErrors())->hasKey('slug');
    }

    public function testUniqueValidated()
    {
        $form = new TagForm(null, [
            'name' => 'Ноутбуки',
            'slug' => 'noutbuki'
        ]);

        expect('Пара name и slug должны быть уникальная', $form->validate())->false();
    }

    public function testTagEdit()
    {
        $tag = Tags::findOne(1);
        $form = new TagForm($tag);

        expect('Должен установиться атрибут name', $form->name)->equals($tag->name);
        expect('Должен установиться атрибут slug', $form->slug)->equals($tag->slug);
        expect('Должны пройти валидацию', $form->validate())->true();
    }
}
