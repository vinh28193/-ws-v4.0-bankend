<?php

/* @var $this yii\web\View */
/* @var $user common\models\Customer */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['secure/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
