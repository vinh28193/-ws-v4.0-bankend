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
echo HeaderContentWidget::widget(['title' => 'Thống kê chung']);
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
            <div class="name">Số dư</div>
            <?php if ($wallet) {
                echo '<div class="info">' . \common\helpers\WeshopHelper::showMoney($wallet->getMoneyAvailable()) . '</div>';
                echo '<a href="/my-weshop/wallet/history.html">Chi tiết >></a>';
            } else {
                echo '<div class="info-upgrade">' . Yii::t('frontend','Upgrade to Prime now') . '</div>';
                echo '<a href="javascript:void(0)" onclick="ws.notifyInfo(\''.Yii::t('frontend','Sorry. Service is under maintenance. Please try again later.').'\',\''.Yii::t('frontend','Notify').'\')">'.Yii::t('frontend','Click here').' >></a>';
            } ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon2"><i class="icon"></i></span>
            <div class="name">Đơn hàng</div>
            <div class="info"><?= $total ?></div>
            <?php echo Html::a('Chi tiết>>', ['/account/order'], ['class' => 'active']); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon3"><i class="icon"></i></span>
            <div class="name">Giỏ hàng</div>
            <div class="info"><?= $totalCart ?></div>
            <a href="/my-cart.html">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon4"><i class="icon"></i></span>
            <div class="name">Khiếu nại</div>
            <div class="info">0</div>
            <a href="#">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="be-box">
            <div class="be-top">
                <div class="title">Thông tin đơn hàng mới cập nhập</div>
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
                            <th scope="col">Mã đơn hàng</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($orders) {
                            foreach ($orders as $order) { ?>
                                <tr>
                                    <td>
                                        <a href="<?= '/my-weshop/order/' . $order->ordercode . '.html' ?>"><?= $order->ordercode ?></a>
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
                                    <div class="no-data text-orange text-center">Chưa có đơn hàng nào!</div>
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
                <div class="title">Trao đổi thông tin mới</div>
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
                            <p><b>Weshop</b> trao đổi mới trong đơn hàng
                                <?php
                                    echo Html::a($order->ordercode,new \yii\web\JsExpression('javascript:void(0);'),[
                                            'data-url' => \yii\helpers\Url::toRoute(['/account/chat/order-chat','code' => $order->ordercode],true),
                                            'data-target' => '#exampleModalCenter',
                                            'data-popup' => 'modal'
                                    ])
                                ?>
                                vào
                                lúc <?= Yii::$app->getFormatter()->asDatetime($order->created_at, "php:d-m-Y  H:i:s") ?>
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
                        <div class="no-data text-orange text-center pt-5">Chưa có thông tin mới</div>
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