<?php

namespace core\helpers;

use core\entities\User\User;
use yii\helpers\Html;

/**
 * Class UserHelpers
 * @package core\helpers
 */
class UserHelpers
{
    /**
     * @return array
     */
    public static function statusList(): array
    {
        return [
            User::STATUS_ACTIVE => 'Активный',
            User::STATUS_WAIT => 'Ожидание',
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function statusName($status): string
    {
        return self::statusList()[$status] ?? '';
    }

    /**
     * @param $status
     * @return string
     */
    public static function statusTag($status): string
    {
        switch ($status) {
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', self::statusName($status), [ 'class' => $class ]);
    }
}