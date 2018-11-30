<?php namespace core\tests\services\shop;

use common\fixtures\TagsFixture;
use core\entities\shop\Tags;
use core\forms\manage\shop\TagForm;
use core\repositories\TagsRepository;
use core\services\manage\shop\TagsManageService;

class TagsManageServiceTest extends \Codeception\Test\Unit
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

    public function testTagCreateSuccess()
    {
        $form = new TagForm(null, [
            'name' => $name = 'name_tag',
            'slug' => $slug = 'slug_tag',
        ]);

        /** @var TagsRepository $repository */
        $service = new TagsManageService(new TagsRepository());
        $tag = $service->create($form);

        expect($tag)->isInstanceOf(Tags::class);
        expect($tag->name)->equals($name);
        expect($tag->slug)->equals($slug);
    }

    public function testTagCreateFail()
    {
        $form = new TagForm(null, [
            'name' => $name = 'Ноутбуки',
            'slug' => $slug = 'noutbuki',
        ]);

        /** @var TagsRepository $repository */
        $service = new TagsManageService(new TagsRepository());

        $this->tester->expectThrowable(\RuntimeException::class, function () use ($service, $form) {
            $service->create($form);
        });
    }

    public function testTagEdit()
    {
        $form = new TagForm(null, [
            'name' => $name = 'new_name_tag',
            'slug' => $slug = 'new_slug_tag',
        ]);

        $repository = new TagsRepository();

        /** @var TagsRepository $repository */
        $service = new TagsManageService($repository);
        $service->edit(1, $form);

        $tag = $repository->byId(1);

        expect($tag->name)->equals($name);
        expect($tag->slug)->equals($slug);
    }

    public function testTagRemove()
    {
        $service = new TagsManageService(new TagsRepository());
        $service->remove(1);

        expect(Tags::findOne(1))->null();
    }
}