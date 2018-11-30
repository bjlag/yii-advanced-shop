<?php

namespace core\tests\entities\shop;

use common\fixtures\TagsFixture;
use core\entities\shop\Tags;

class TagsTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'tags' => [
                'class' => TagsFixture::class,
                'dataFile' => codecept_data_dir() . 'tags.php'
            ]
        ]);
    }

    protected function _after()
    {
    }

    public function testTagCreate()
    {
        $tag = Tags::create(
            $name = 'Ноутбук',
            $slug = 'noutbuk'
        );

        expect($tag->name)->equals($name);
        expect($tag->slug)->equals($slug);
    }

    public function testTagEdit()
    {
        $tag = Tags::findOne(1);

        $tag->edit(
            $name = 'new_name',
            $slug = 'new_slug'
        );

        expect($tag->name)->equals($name);
        expect($tag->slug)->equals($slug);
    }
}