<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>!</p>

    <p>Перейдите по ссылке ниже для подтверждения емейла:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
