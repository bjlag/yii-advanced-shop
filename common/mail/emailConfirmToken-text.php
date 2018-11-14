<?php

/* @var $this yii\web\View */
/* @var $user common\entities\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-email', 'token' => $user->email_confirm_token]);
?>
Здравствуйте, <?= $user->username ?>!

Перейдите по ссылке ниже для подтверждения емейла:

<?= $confirmLink ?>
