<?php
namespace common\fixtures;

use core\entities\User\Network;
use yii\test\ActiveFixture;

class UserNetworkFixture extends ActiveFixture
{
    public $modelClass = Network::class;
}