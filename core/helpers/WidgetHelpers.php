<?php

namespace core\helpers;

use kartik\widgets\DatePicker;
use yii\base\Model;

/**
 * Class WidgetHelpers
 * @package core\helpers
 */
class WidgetHelpers
{
    /**
     * @param Model $searchModel
     * @param string $attribute
     * @param string $attribute2
     * @return string
     * @throws \Exception
     */
    public static function DatePickerRange(Model $searchModel, string $attribute, string $attribute2)
    {
        return DatePicker::widget([
            'model' => $searchModel,
            'type' => DatePicker::TYPE_RANGE,
            'attribute' => $attribute,
            'attribute2' => $attribute2,
            'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.m.yyyy',
                'todayHighlight' => true,
            ]
        ]);
    }
}