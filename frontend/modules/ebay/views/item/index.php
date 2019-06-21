<?php


use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */

$this->params = ['Home' => '/','eBay' => '/ebay.html', $item->item_name => 'javascript:void(0);'];
$this->title = Yii::t('frontend','Detail Product Ebay |').' '.$item->item_name;

echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'ebay-item-detail',
        'class' => 'row'
    ]
]);
if ($item->customer_feedback && false) {
    ?>
    <div class="detail-block-2 box-shadow">
        <div class="row">
            <div class="col-md-12">
                <div class="title"><?= Yii::t('frontend', 'Customer Reviewed On {portal}', ['portal' => '<a href="//ebay.com" target="_blank">eBay.com</a>']) ?>
                    :
                </div>
            </div>
            <div class="col-md-12 row rating-feedback">
                <?php
                foreach ($item->customer_feedback as $value) {
                    ?>
                    <div class="col-md-12">
                        <div class="avatar-feedback">
                            <span>
                                <img src="/img/no_image.png">
                            </span>
                            <span class="time-feedback">
                                <?= Yii::t('frontend',$value['day_review']) ?>
                            </span>
                        </div>
                        <div class="content-feedback"><?= $value['review_content'] ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}
?>
