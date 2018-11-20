<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Управление привязанными соцсетями';

$this->params['breadcrumbs'][] = ['label' => 'Кабинет', 'url' => ['cabinet/default']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<h2>Добавить</h2>
<?= yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['auth/network/attach']
]); ?>