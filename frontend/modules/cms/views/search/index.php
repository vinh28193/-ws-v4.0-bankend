<?php

use common\helpers\WeshopHelper;
use common\models\cms\WsProduct;
use frontend\widgets\breadcrumb\BreadcrumbWidget;
use frontend\widgets\cms\ProductWidget;

/**
 * @var $data array
 */
$keyword = Yii::$app->request->get('keyword');
?>
<?= BreadcrumbWidget::widget(['portal' => 'home']) ?>
<div class="search-content">
    <?php
    foreach ($data as $portal => $datum){ ?>
        <div class="title-box">
            <div class="left">
                <img src="<?= WeshopHelper::getLogoByPortal($portal) ?>" width="100" alt=""/>
                <span>Tìm kiếm “<?= $keyword ?>”</span>
            </div>
            <div class="right">
                <a href="/<?= $portal?>/search/<?= $keyword ?>.html" class="see-all">Xem tất cả</a>
            </div>
        </div>

        <div class="product-list row">
            <?php
            $ind = 0;
            foreach ($datum['products'] as $p) {
                $ind ++;
                if($ind > 8){
                    break;
                }
                $product = new WsProduct();
                $product->setAttributes($p, false);
                $product->start_price = $p['retail_price'];
                $product->name = $p['item_name'];
                $product->calculated_sell_price = $product->sell_price;
                $product->calculated_start_price = $product->start_price;
                $product->item_url = WeshopHelper::generateUrlDetail($portal,$product->name,$p['item_id']);
                echo ProductWidget::widget(['type' => ProductWidget::TYPE_COL, 'product' => $product->getAttributes()]);
            } ?>
        </div>
    <?php } ?>
</div>