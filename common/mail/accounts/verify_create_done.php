<?php
use Yii;
if(YII_ENV=='dev')
{
    //$api_host=Yii::$app->params['dev_api_url'];
    $domain = 'http://weshop-v4.front-end-ws.local.vn';
}
else if(YII_ENV=='prod')
{
    $domain = 'https://weshop.com.vn';
}

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
                                                                   target="_blank">My Account</a></td>
                                <td style="padding:20px 0 17px 15px" width="33.33%" align="center">
                                    <a href="<?= $domain; ?>" target="_blank">
                                        <img class="CToWUd" src="<?= $domain; ?>/mail/image/weshop_logo.png"
                                             alt="Weshop" width="116" height="44"/>
                                    </a>
                                </td>
                                <td style="font-size:11px;font-weight: bold;" align="right" width="33.33%">
                                    <img style="display: inline-block;vertical-align: middle; margin-right: 5px"
                                         src="<?= $domain; ?>/mail/image/phone2.png"/>
                                    <span style="color:#e67425;text-decoration:none; display: inline-block;vertical-align: middle; padding-right: 20px;"
                                          target="_blank">Hotline: 0932 277 572</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background:#ffffff; border-bottom:2px solid #e67425; color:#2796b6; font-size:24px; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif; padding:15px 0px 15px;font-weight: bold;"
                        align="center">
                        YÊU CẦU XÁC THỰC TÀI KHOẢN
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="padding:0 0 0 0;border:1px solid #e3e3e3;border-top:none;border-bottom:none;background: #fff"
                               border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td colspan="3" style="" align="center">
                                    <img src="<?= $domain; ?>/mail/image/shadow.png" width="100%"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="padding:20px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                Kính chào Quý khách <b><?= $accountName; ?>,</b></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:20px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                Chúc mừng quý khách đã trở thành thành viên của <a
                                                    href="<?= $domain; ?>">WeShop Việt Nam</a>
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
                                                <h4 style="font-size: 14px; margin: 0 0 15px;">Thông tin đăng ký</h4>
                                                <p>
                                                    <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                                                </p>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td style="padding: 15px;border: 1px solid #e3e3e3;"
                                                            width="25%">
                                                            <b>Tên đăng nhập</b>
                                                        </td>
                                                        <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top"
                                                            width="75%">
                                                            <?= $accountName; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 15px;border: 1px solid #e3e3e3;"
                                                            width="25%">
                                                            <b>Email</b>
                                                        </td>
                                                        <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top"
                                                            width="75%">
                                                            <?= $email; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 15px;border: 1px solid #e3e3e3;"
                                                            width="25%">
                                                            <b>Số điện thoại</b>
                                                        </td>
                                                        <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top"
                                                            width="75%">
                                                            <?= $phoneNumber; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p style="padding: 20px 0 0 0">Để xác thực tài khoản, mời quý khách
                                                    click vào đường dẫn dưới đây:<br/>
                                                    <a href="<?= $verifyLink; ?>"
                                                       style="display: block; width: 500px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis"><?= $verifyLink; ?></a>
                                                </p>
                                                <p>Cám ơn quý khách đã đăng ký thành viên tại <a href="<?= $domain; ?>">WeShop
                                                        Việt Nam</a></p>
                                                <p><b>BẮT ĐẦU MUA SẮM NGAY TẠI <a href="<?= $domain; ?>">WESHOP VIỆT
                                                            NAM</a></b></p>

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
                                                Mọi thắc mắc và góp ý, Quý khách vui lòng liên hệ với chúng tôi
                                                qua:
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                Email hỗ trợ: <font color="#0388cd"><a
                                                        href="mailto:support-vn@weshop.asia" target="_blank">support-vn@weshop.asia</a></font>
                                                hoặc
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                Tổng đài Chăm sóc khách hàng: 1900 6755 hoặc Hotline : 0932 277 572
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:20px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666">
                                                Weshop trân trọng cảm ơn và rất hân hạnh được phục vụ Quý khách.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;font-style:italic">
                                                <b>*Quý khách vui lòng không trả lời email này*</b></td>
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
            <img src="<?= $domain; ?>/mail/image/mailbox.png" alt="" width="694"/>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
