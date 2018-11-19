<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/reset', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Здравствуйет, <?= Html::encode($user->username) ?>!</p>

    <p>Перейдите по ссылке ниже для установки нового пароля:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
