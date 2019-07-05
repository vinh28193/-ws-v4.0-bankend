<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
$domain = Yii::$app->request->hostInfo;
if(YII_ENV == 'prod'){
    $domain =   str_replace(['http://','https"//'],'',$domain);
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<table bgcolor="#2796b6" border="0" cellpadding="0" cellspacing="0" width="100%"
       style=" width:600px;margin:5px auto;font-family: Arial,Helvetica,sans-serif;font-size: 12px;">
    <tbody>
    <tr bgcolor="#141c2e" `>
        <td style="padding:20px 0 17px 15px" align="center">
            <a href="<?= $domain; ?>" target="_blank">
                <img class="CToWUd" src="<?= $domain; ?>/images/logo/weshop-01.png"
                     alt="Weshop" width="116" height="44">
            </a>
        </td>
    </tr>
    <tr>
        <td>
            <?= $content ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#131a22" style="padding: 20px 0">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                        <img height="32px" src="/images/logo/weshop-01.png" alt="">
                    </td>
                    <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                        WESHOP VIET NAM - WORLD WIDE SHOPPING MADE EASY
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
