<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */

if (YII_ENV == 'dev') {
    $domain = Yii::$app->params['Url_FrontEnd'] ? Yii::$app->params['Url_FrontEnd'] : 'http://weshop-v4.front-end-ws.local.vn';
} else if (YII_ENV == 'prod') {
    $domain = 'https://weshop.com.vn';
}
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['secure/verify', 'token' => $user->auth_key]);
?>
Chao <?= $user->username ?>, Ban tao thanh cong tai khoan tren <?= $domain ?>
Email: <?= $user->email ?>, Phone: <?= $user->phone ?>
