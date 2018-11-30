<?php

namespace core\entities\shop;

/**
 * This is the model class for table "{{%shop_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * Создание метки.
     * @param string $name
     * @param string $slug
     * @return Tags
     */
    public static function create(string $name, string $slug): self
    {
        return new static([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    /**
     * Редактирование метки.
     * @param string $name
     * @param string $slug
     */
    public function edit(string $name, string $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name', 'slug'], 'unique', 'targetAttribute' => ['name', 'slug']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'slug' => 'Транслит',
        ];
    }
}
