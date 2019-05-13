<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['secure/reset-password', 'token' => $user->reset_password_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
