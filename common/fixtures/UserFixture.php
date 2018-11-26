<?php
namespace common\fixtures;

use core\entities\User\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}