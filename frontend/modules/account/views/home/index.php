<?php
/**
 * @var $this \yii\web\View
 * @var $wallet \Accouting\Merchantinfo
 * @var $totalCart int
 * @var $total int
 */

use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\helpers\Html;

$chat = false;
$this->title = 'My WeShop';
echo HeaderContentWidget::widget(['title' => Yii::t('frontend', 'General statistics')]);
$js = <<<JS
    $('a[data-popup=modal]').on('click',function(event) {
        event.preventDefault();
        var uri = $(this).data('url');
        $('div#exampleModalCenter').modal('show').find('#modalContent').load(uri);
    });
JS;
$this->registerJs($js);
?>
<div class="row">
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon1"><i class="icon"></i></span>
            <div class="name"><?= Yii::t('frontend', 'General statistics') ?></div>
            <?php if ($wallet) {
                echo '<div class="info">' . \common\helpers\WeshopHelper::showMoney($wallet->getMoneyAvailable()) . '</div>';
                echo '<a href="/my-weshop/wallet/history.html">'.Yii::t('frontend','Detail >>').'</a>';
            } else {
                echo '<div class="info-upgrade">' . Yii::t('frontend','Upgrade to Prime now') . '</div>';
                echo '<a href="javascript:void(0)" onclick="ws.notifyInfo(\''.Yii::t('frontend','Sorry. Service is under maintenance. Please try again later.').'\',\''.Yii::t('frontend','Notify').'\')">'.Yii::t('frontend','Click here').' >></a>';
            } ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon2"><i class="icon"></i></span>
            <div class="name"><?= Yii::t('frontend', 'Order') ?></div>
            <div class="info"><?= $total ?></div>
            <?php echo Html::a('Chi tiết>>', ['/account/order'], ['class' => 'active']); ?>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <div class="be-box">
            <div class="be-top">
                <div class="title"><?= Yii::t('frontend', 'New notify order') ?></div>
                <div class="top-action">
                    <a href="#">-</a>
                    <a href="#">x</a>
                </div>
            </div>
            <div class="be-body style-croll pb-1" style="height: 15em; overflow-y: scroll;">
                <div class="be-table table-scrollbar">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"><?= Yii::t('frontend', 'Order Code') ?></th>
                            <th scope="col"><?= Yii::t('frontend', 'Status') ?></th>
                            <th scope="col"><?= Yii::t('frontend', 'Time') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($orders) {
                            foreach ($orders as $order) { ?>
                                <tr>
                                    <td>
                                        <?php if ($order->total_paid_amount_local > 0) { ?>
                                            <a href="javascript:void(0)"><?= $order->ordercode ?></a>
                                        <?php } else { ?>
                                            <a href="<?= 'checkout.html?code=' . $order->ordercode ?>"
                                               target="_blank"><?= $order->ordercode ?></a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $order->current_status ?></td>
                                    <td>
                                        <?php
                                        if ($order->current_status == 'NEW') {
                                            echo Yii::$app->getFormatter()->asDatetime($order->created_at);
                                        } else {
                                            echo Yii::$app->getFormatter()->asDatetime($order->updated_at);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="3">
                                    <div class="no-data text-orange text-center"><?= Yii::t('frontend', "Haven't order") ?></div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="be-box">
            <div class="be-top">
                <div class="title"><?= Yii::t('frontend', 'New chat') ?></div>
                <div class="top-action">
                    <a href="#">-</a>
                    <a href="#">x</a>
                </div>
            </div>
            <div class="be-body style-croll" style="height: 15em; overflow-y: scroll;">
                <ul class="new-update">
                    <?php foreach ($orders as $order) {
                        $chat = \common\modelsMongo\ChatMongoWs::find()->where([
                            'and',
                            ['Order_path' => $order->ordercode],
                            ['type_chat' => 'WS_CUSTOMER']
                        ])->orderBy(['created_at' => SORT_DESC])->one();
                        ?>
                        <li>
                            <p><b>Weshop</b> <?= Yii::t('frontend', 'chat with new order') ?>
                                <?php
                                echo Html::a($order->ordercode,new \yii\web\JsExpression('javascript:void(0);'),[
                                    'data-url' => \yii\helpers\Url::toRoute(['/account/chat/order-chat','code' => $order->ordercode],true),
                                    'data-target' => '#exampleModalCenter',
                                    'data-popup' => 'modal'
                                ])
                                ?>
                                <?= Yii::t('frontend', 'at') ?> <?= Yii::$app->getFormatter()->asDatetime($order->created_at, "php:d-m-Y  H:i:s") ?>
                            </p>
                            <?php if ($chat) { ?>
                                <div class="mess-content mb-1">
                                    <i class="logo"><img src="../img/weshop_small_logo.png" alt=""/></i>
                                    <span><?= $chat->message ?></span>
                                </div>
                            <?php } ?>
                        </li>
                    <?php } ?>
                    <?php if (!$chat) { ?>
                        <div class="no-data text-orange text-center pt-5"><?= Yii::t('frontend', 'It\'s empty.') ?></div>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="card m-b-0">
                        <div class="card-body">
                            <div id="modalContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
