<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $paymentTransaction common\models\PaymentTransaction */
/* @var $storeManager common\components\StoreManager */

$domain = Yii::$app->request->hostInfo;
?>
<table bgcolor="#f0f8ff" style="color: #ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td>
            <table style="color: #666666" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:20px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        Kính chào Quý khách <b>,</b></td>
                </tr>
                <tr>
                    <td style="padding:20px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        Chân thành cảm ơn Quý khách đã mua sắm tại <a href="<?= $domain; ?>">WeShop
                            Việt Nam</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding:00px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <p>Chúng tôi hy vọng Quý khách hài lòng với trải nghiệm mua sắm và
                            các sản phẩm đã chọn.<br/><a href="<?= $domain; ?>">WeShop VIệt
                                Nam</a> vừa nhận
                            được thông tin đặt hàng của quý khách với chi tiết đơn hàng như
                            sau:</p>
                    </td>
                </tr>

                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 20px;">
            <table style="border-collapse:collapse;color: #666666; border: 1px solid slategrey" bgcolor="#ffffff"
                   cellpadding="0"
                   cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:20px 20px 20px 20px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="50%" style="vertical-align: top">
                                    <h4 style="font-size: 14px; margin: 0 0 15px;text-transform: uppercase;">
                                        Thông tin giao dich</h4>
                                    <p>
                                        <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                                    </p>
                                    <p><b>Mã giao dịch: </b># </p>
                                    <p><b>Trạng thái: </b># </p>
                                    <p><b>Ngày / Giờ:</b><?= date(' H:i Y-m-d', strtotime('now')); ?>
                                    </p>
                                    <!-- <p><b>Giờ:</b> </p> -->
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
        <td style="padding:10px 20px">
            <table style="border-collapse:collapse;color: #666666; border: 1px solid slategrey" bgcolor="#ffffff"
                   cellpadding="0"
                   cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:15px 20px 1px 20px">
                        <table style="border-collapse:collapse;" border="0" cellpadding="0"
                               cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:bold;padding:0 0 10px 0"
                                    align="left">
                                    <h4 style="font-size: 14px; margin: 0 0 15px;text-transform: uppercase;">
                                        Chi tiết đơn hàng</h4>
                                    <p>
                                        <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:bold;padding:0 0 10px 0">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td colspan="1"
                                                style="width:50%;padding:0.75rem 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                                                OrderCode : sdaSs
                                            </td>
                                            <td colspan="1"
                                                style="width:20%;padding:0.75rem 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                                                Amazon
                                            </td>
                                            <td colspan="1"
                                                style="width:30%;padding:0.75rem 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                                                Seller : sdaSs
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
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>