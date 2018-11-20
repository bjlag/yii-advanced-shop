<?php

/* @var $this yii\web\View */

$this->title = 'Кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

<p>
    <a href="<?= \yii\helpers\Url::to(['/cabinet/network'])?>">Управление сициальными сетями</a>
</p>
