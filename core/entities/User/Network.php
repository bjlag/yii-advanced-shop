<?php

namespace core\entities\User;

use Webmozart\Assert\Assert;

/**
 * This is the model class for table "user_network".
 *
 * @property int $id
 * @property int $user_id
 * @property string $network
 * @property string $identity
 *
 * @property User $user
 */
class Network extends \yii\db\ActiveRecord
{
    /**
     * @param string $network
     * @param string $identity
     * @return Network
     */
    public static function create(string $network, string $identity): self
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        return new static([
            'network' => $network,
            'identity' => $identity
        ]);
    }

    /**
     * @param string $network
     * @param string $identity
     * @return bool
     */
    public function isFor(string $network, string $identity): bool
    {
        return $this->network === $network && $this->identity === $identity;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_network}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
