<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\User\UpdateUserForm */
/* @var $user \core\entities\User\User */

$userName = ucfirst($user->username) . " (ID:{$user->id})";

$this->title = 'Изменить пользователя: ' . $userName;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $userName,
    'url' => ['view', 'id' => $user->id]
];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput() ?>
        <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'status')->dropDownList(\core\helpers\UserHelpers::statusList()) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
