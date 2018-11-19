<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/reset', 'token' => $user->password_reset_token]);
?>
Здравствуйте, <?= $user->username ?>!

Перейдите по ссылке ниже для установки нового пароля:

<?= $resetLink ?>
