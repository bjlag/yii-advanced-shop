<?php

namespace core\forms\manage\shop;

use core\entities\shop\Tags;
use yii\base\Model;

/**
 * Class TagForm
 */
class TagForm extends Model
{
    public $name;
    public $slug;

    /** @var Tags */
    private $tag;

    /**
     * TagForm constructor.
     * @param Tags|null $tag
     * @param array $config
     */
    public function __construct(Tags $tag = null, array $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->tag = $tag;
        }

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [
                ['name', 'slug'],
                'unique',
                'targetClass' => Tags::class,
                'targetAttribute' => ['name', 'slug'],
                'filter' => $this->tag ? ['<>', 'id', $this->tag->id] : ''
            ],
            ['slug', 'match', 'pattern' => '/^[a-z0-9_-]+$/s'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'slug' => 'Транслит'
        ];
    }
}