<?php

namespace core\helpers;

use yii\helpers\VarDumper;

/**
 * Вспомомогальный класс для отладки приложения.
 * @package core\helpers
 */
class D
{
    /**
     * Вывести дам данных и завершить приложение.
     * @param mixed $data
     */
    public static function end($data)
    {
        echo VarDumper::dumpAsString($data, 5, true);
        exit();
    }

    /**
     * Добавить дамп данных в лог.
     * @param mixed $data
     */
    public static function log($data)
    {
        \Yii::info(VarDumper::dumpAsString($data, 5), '_');
    }
}
