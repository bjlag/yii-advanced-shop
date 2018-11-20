<?php

/* @var $this yii\web\View */
/* @var $model core\entities\User\User */

$userName = ucfirst($model->username) . " (ID:{$model->id})";

$this->title = 'Изменить пользователя: ' . $userName;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $userName,
    'url' => ['view', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
