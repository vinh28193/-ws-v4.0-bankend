<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\User */

if (YII_ENV == 'dev') {
    $domain = Yii::$app->params['Url_FrontEnd'] ? Yii::$app->params['Url_FrontEnd'] : 'http://weshop-v4.front-end-ws.local.vn';
} else if (YII_ENV == 'prod') {
    $_domain = $dommain;
}

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['secure/verify', 'token' => $user->auth_key]);
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['secure/verify', 'token' => $user->auth_key]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>WeShop</title>
</head>

<body style="margin:0;padding:0;background-color:#2796b6">
<table bgcolor="#2796b6" border="0" cellpadding="0" cellspacing="0" width="100%"
       style="font-family: Arial,Helvetica,sans-serif;font-size: 12px;color: rgb(102, 102, 102);">
    <tbody>
    <tr>
        <td>
            <table style="border-collapse:collapse" align="center" border="0" cellpadding="0"
                   cellspacing="0" width="600">

                <tbody>
                <tr>
                    <td>
                        <table border="0" cellpadding="0" bgcolor="#ffffff" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td style="color:#999999;font-family:Arial,Helvetica,sans-serif;font-size:11px; padding-left: 20px;"
                                    align="left" width="33.33%"><a href="#" style="color:#999999;text-decoration:none"
                                                                   target="_blank"><?= Yii::t('frontend','My Account') ?></a></td>
                                <td style="padding:20px 0 17px 15px" width="33.33%" align="center">
                                    <a href="<?= $_domain; ?>" target="_blank">
                                        <img class="CToWUd" src="<?= $_domain; ?>/mail/image/weshop_logo.png"
                                             alt="Weshop" width="116" height="44"/>
                                    </a>
                                </td>
                                <td style="font-size:11px;font-weight: bold;" align="right" width="33.33%">
                                    <img style="display: inline-block;vertical-align: middle; margin-right: 5px"
                                         src="<?= $_domain; ?>/mail/image/phone2.png"/>
                                    <span style="color:#e67425;text-decoration:none; display: inline-block;vertical-align: middle; padding-right: 20px;"
                                          target="_blank"><?= Yii::t('frontend','Hotline: 19006755') ?> Hotline: 19006755</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background:#ffffff; border-bottom:2px solid #e67425; color:#2796b6; font-size:24px; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif; padding:15px 0px 15px;font-weight: bold;"
                        align="center">
                        <?= Yii::t('frontend','REQUEST FOR AUTHENTICATION OF ACCOUNT') ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="padding:0 0 0 0;border:1px solid #e3e3e3;border-top:none;border-bottom:none;background: #fff"
                               border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td colspan="3" style="" align="center">
                                    <img src="<?= $_domain; ?>/mail/image/shadow.png" width="100%"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="padding:20px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                <?= Yii::t('frontend','Welcome') ?> <b><?= Html::encode($user->username) ?>,</b></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:20px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                <?= Yii::t('frontend','Congratulations on your membership') ?>
                                                <a href="<?= $_domain; ?>"><?= Yii::t('frontend','WeShop Viet Nam') ?></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 0 20px">
                                    <table style="border: 1px solid #e3e3e3;" cellspacing="0" cellpadding="0"
                                           width="100%">
                                        <tr>
                                            <td style="padding: 20px">
                                                <h4 style="font-size: 14px; margin: 0 0 15px;"><?= Yii::t('frontend','Registration information') ?></h4>
                                                <p>
                                                    <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                                                </p>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td style="padding: 15px;border: 1px solid #e3e3e3;"
                                                            width="25%">
                                                            <b><?= Yii::t('frontend','user name') ?></b>
                                                        </td>
                                                        <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top"
                                                            width="75%">
                                                            <?= Html::encode($user->username) ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 15px;border: 1px solid #e3e3e3;"
                                                            width="25%">
                                                            <b>Email</b>
                                                        </td>
                                                        <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top"
                                                            width="75%">
                                                            <?= Html::encode($user->email) ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 15px;border: 1px solid #e3e3e3;"
                                                            width="25%">
                                                            <b><?= Yii::t('frontend','phone number') ?> </b>
                                                        </td>
                                                        <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top"
                                                            width="75%">
                                                            <?= Html::encode($user->phone) ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p style="padding: 20px 0 0 0"><?= Yii::t('frontend','To verify your account, please click on the link below:') ?><br/>
                                                    <a href="<?= $verifyLink; ?>" style="display: block; width: 500px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis"><?= $verifyLink; ?></a>
                                                </p>
                                                <p><?= Yii::t('frontend','Thank you for registering your membership at ') ?>  <a href="<?= $_domain; ?>"> <?= Yii::t('frontend','WeShop Viet Nam') ?> </a></p>
                                                <p><b><?= Yii::t('frontend','START SHOPPING NOW') ?> <a href="<?= $_domain; ?>"> <?= Yii::t('frontend','WESHOP VIET NAM') ?> </a></b></p>

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding-top: 20px">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                <?= Yii::t('frontend','Any questions and suggestions, please contact us') ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                <?= Yii::t('frontend','Email support:') ?>   <font color="#0388cd"><a
                                                            href="mailto:support@weshop.asia" target="_blank">support@weshop.asia</a></font>
                                                hoặc
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                <?= Yii::t('frontend','Customer Care Call Center: 1900 6755 ') ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:20px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                <?= Yii::t('frontend','Customer Care Call Center: 1900 6755 ') ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;font-style:italic">
                                                <b> <?= Yii::t('frontend','* Please do not reply to this email *') ?> </b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <img src="<?= $_domain; ?>/mail/image/mailbox.png" alt="" width="694"/>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
