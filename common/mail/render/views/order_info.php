<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-03
 * Time: 13:16
 */

use yii\helpers\Html;
use common\mail\Template;
use common\models\model\Order;
/** @var $this \yii\web\View */
/** @var $template common\mail\Template */
$model = $template->getActiveModel();
$currency = $template->getReplace(Template::NEEDLE_CURRENCY_CODE);
$totalAmount = $template->getReplace(Template::NEEDLE_TOTAL_AMOUNT);
$totalPaidAmount = $template->getReplace(Template::NEEDLE_TOTAL_PAID_AMOUNT);
$paymentMethod = $template->getReplace(Template::NEEDLE_PAYMENT_METHOD);
$paymentProvider = $template->getReplace(Template::NEEDLE_PAYMENT_PROVIDER);
$orderTracking = $template->getReplace(Template::NEEDLE_ORDER_TRACKING_URL);

//Todo Yii:t
?>

<table style="border-collapse:collapse;border:1px solid #e3e3e3"
       bgcolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td style="padding:15px 20px 1px 20px">
            <table style="border-collapse:collapse" border="0" cellpadding="0"
                   cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:bold;color:#25396c;padding:0 0 10px 0"
                        colspan="4" align="left">
                        <h4 style="font-size: 14px; margin: 0 0 15px;color: rgb(102, 102, 102);text-transform: uppercase;">
                            Chi tiết đơn hàng</h4>
                        <p>
                            <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:0 10px 10px 10px" align="left" width="47%"><?= Yii::t('frontend','Product');?></td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:0 10px 10px 10px" align="left" width="15%"><?= Yii::t('frontend','Quantity');?></td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:0 10px 10px 10px" align="left" width="19%"><?= Yii::t('frontend','Amount');?></td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:0 0 10px 0" align="right" width="19%"><?= Yii::t('frontend','Total Amount');?></td>
                </tr>
                <?php if($model instanceof Order): ?>
                <?php foreach($model->orderItems as $item): ?>
                    <tr>
                        <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px; vertical-align: middle"
                            align="left" bgcolor="#f6f6f6">
                            <a href="<?= $item->sourceId; ?>"
                               style="display: inline-block;vertical-align: middle;width: 50px;height: 50px;border:1px solid #ccc;">
                                <img src="<?= $item->image; ?>"
                                     style="max-width: 50px; max-height: 50px"/>
                            </a>
                            <span style="display: inline-block;vertical-align: middle; width: 160px;padding-left: 5px"><?= $item->Name; ?> <?=$item->specifics?> </span>
                        </td>
                        <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px"
                            align="center" bgcolor="#f6f6f6"><?= $item->quantity; ?>
                        </td>
                        <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px"
                            align="center"
                            bgcolor="#f6f6f6"> <?= $item->TotalAmountInLocalCurrencyDisplay; ?>
                        </td>
                        <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px"
                            align="right"
                            bgcolor="#f6f6f6"><?= $item->TotalAmountInLocalCurrencyDisplay; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3;border-top:dashed 1px #666666"
                        colspan="4" align="right">
                        <?= Html::a(Yii::t('frontend','(Tracking Url)'),$orderTracking);?>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3;border-top:dashed 1px #666666"
                        colspan="2" align="left"><?= Yii::t('frontend','Product sell price');?>
                    </td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3;border-top:dashed 1px #666666"
                        colspan="2"
                        align="right"><?= $totalAmount . $currency; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:13.5px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="left" bgcolor="#fffbe2">
                        <?= Yii::t('frontend','Total paid amount');?>
                    </td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="right"
                        bgcolor="#fffbe2"><?= $totalPaidAmount .' '. $currency;; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:13.5px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="left" bgcolor="#fffbe2"><?= Yii::t('frontend','Total unpaid amount');?>
                    </td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="right"
                        bgcolor="#fffbe2"><?= ($totalAmount - $totalPaidAmount) .' '. $currency ;?>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="left"><?= Yii::t('frontend','Payment method');?>
                    </td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="right"><?= $paymentMethod .' / '. $paymentProvider;?>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px"
                        colspan="2" align="left"><?= Yii::t('frontend','Status');?>
                    </td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px"
                        colspan="2" align="right"><?= Yii::t('frontend','Unpaid');?>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:bold;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="left"><?= Yii::t('frontend','Note');?>
                    </td>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666666;padding:10px 10px 10px 10px;border-bottom:1px solid #e3e3e3"
                        colspan="2" align="right"><?= $model->note; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
