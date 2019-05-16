<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-02
 * Time: 15:28
 */

use yii\helpers\Html;
use common\mail\Template;
use common\models\model\OrderPayment;
use common\models\model\OrderPaymentRequest;
/** @var $this \yii\web\View */
/** @var $template common\mail\Template */
$model = $template->getActiveModel();
$store = $template->website;

$currency = $template->getReplace(Template::NEEDLE_CURRENCY_CODE);
$totalAmount = $template->getReplace(Template::NEEDLE_TOTAL_AMOUNT);
$totalPaidAmount = $template->getReplace(Template::NEEDLE_TOTAL_PAID_AMOUNT);

echo Html::tag('h4',Yii::t('frontend', 'List of Products'),['style' => 'font-size: 14px; margin: 0 0 15px;']);
echo '<p><i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i></p>';
?>
<table cellpadding="0" cellspacing="0" width="100%">
    <?php if($model instanceof OrderPayment && count($requests = $model->orderPaymentRequests) > 0): ?>
        <?php foreach ($requests as $request): ?>
            <?php
            /** @var $orderItem \common\models\model\OrderItem */
            $orderItem = $request->orderItem;
            ?>
            <?php if ($request->type == OrderPaymentRequest::TYPE_ADDFEE): ?>
                <tr>
                    <td style="padding: 15px;border: 1px solid #e3e3e3;" width="25%" align="center">
                        <a href="<?= $orderItem->sourceId ?>">
                            <img src="<?= $orderItem->image ?>" width="100" height="100"/>
                        </a>
                    </td>
                    <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top" width="75%">
                        <p style="margin-top: 0"><a href="<?= $orderItem->sourceId ?>"><?= $orderItem->Name; ?></a></p>
                        <p style="margin-top: 0"><?= Yii::t('frontend','<b>Product ID:</b> {productId}',['productId' => $orderItem->ParentSku]);?></p>
                        <p style="margin-top: 0"><?= Yii::t('frontend','<b>Reason for additional charge:</b> {processNote}',['processNote' => $request->process_note]);?></p>
                        <p style="margin-top: 0"><?= Yii::t('frontend','<b>Amount:</b> {amount}',['amount' => $store->showMoney($request->amount_local)]);?></p>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td style="padding: 15px;border: 1px solid #e3e3e3;" width="25%" align="center">
                        <a href="<?= $orderItem->sourceId ?>">
                            <img src="<?= $orderItem->image ?>" width="100" height="100"/>
                        </a>
                    </td>
                    <td style="padding: 15px; border: 1px solid #e3e3e3;border-left: none; vertical-align: top" width="75%">
                        <p style="margin-top: 0"><a href="<?= $orderItem->sourceId ?>"><?= $orderItem->Name; ?></a></p>
                        <p style="margin-top: 0"><?= Yii::t('frontend','<b>Product ID:</b> {productId}',['productId' => $orderItem->ParentSku]);?></p>
                        <p style="margin-top: 0"><?= Yii::t('frontend','<b>Reason for refund:</b> {processNote}',['processNote' => $request->process_note]);?></p>
                        <p style="margin-top: 0"><?= Yii::t('frontend','<b>Amount:</b> {amount}',['amount' => $store->showMoney($request->amount_local)]);?></p>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<p>
    <?= Yii::t('frontend','<b>Total of Additional Charges:</b> {totalAmount}',['totalAmount' => $store->showMoney($model->total_local_amount)]);?>
<p>
    <?php  if($store->isWSVN()): ?>
        <?php if($model->payment_method === 'COD'): ?>
            Quý khách vui lòng thanh toán số tiền thu thêm cho nhân viên giao vận khi nhận hàng.
        <?php else: ?>
            Quý khách vui lòng thanh toán số tiền thu thêm bằng cách ấn nút " THANH TOÁN NGAY " để Weshop hoàn thành việc vận chuyển hàng đến tay Quý khách
        <?php endif; ?>
    <?php elseif ($store->isWSID()): ?>
        Silahkan lakukan pembayaran biaya tambahan tersebut agar pesanan dapat kami proses lebih lanjut.
    <?php elseif ($store->isWSMY()): ?>
        Kindly make your additional payment to proceed with the purchase. Your item is now on transit from US to Malaysia so please kindly wait for another 7-10 days.
        In the meantime, please kindly complete your additional payment so we can deliver your item immediately upon item arrival to Malaysia.
    <?php endif; ?>

</p>
