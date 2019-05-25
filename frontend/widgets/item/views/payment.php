<?php
/**
 * @var $item \common\products\BaseProduct
 */

use common\helpers\WeshopHelper;
$sellerCurrent = Yii::$app->request->get('seller');
$sellerCurrent = $sellerCurrent ? $sellerCurrent : $item->getSeller();
$url = function ($seller_id){
    $param = [explode('?',\yii\helpers\Url::current())[0]];
    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
    $param['seller'] = $seller_id;
    if(isset($param['id'])){
        unset($param['id']);
    }
    if(isset($param['name'])){
        unset($param['name']);
    }
//           $param['portal'] = $portal;
    return Yii::$app->getUrlManager()->createUrl($param);
};
$instockQuanty = 0;
if($item->available_quantity){
    $instockQuanty = $item->quantity_sold ? $item->available_quantity - $item->quantity_sold : $item->available_quantity;
}
?>

<div class="border-block payment-info">
    <?php if(strtolower($item->type) == 'ebay') {?>
    <div class="qty form-inline" id="" style="display: block; font-size: 10px">
        <b id="instockQuantity" ><?= $instockQuanty ?></b><i> sản phẩm có thể mua</i>
    </div>
    <?php } ?>
    <div class="qty form-inline" id="quantityGroup" style="display: <?= $sellerCurrent ? 'block' : 'none' ?>;">
        <label>Số lượng:</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary btnQuantity" type="button" data-href="down">-</button>
            </div>
            <input type="text" class="form-control" id="quantity" value="1"/>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary btnQuantity" type="button" data-href="up">+</button>
            </div>
        </div>
    </div>
    <div class="qty form-inline" id="outOfStock" style="display: <?= !$sellerCurrent ? 'block' : 'none' ?>;">
        <label style="color: red">Sản phẩm hết hàng</label>
    </div>
    <div class="action-box" style="display: <?=$instockQuanty > 0 ? 'block' : 'none';?>">
        <button type="button" id="buyNowBtn" class="btn btn-block btn-buy" style="display: <?= $sellerCurrent ? 'block' : 'none' ?>">Mua ngay</button>
        <button type="button" id="quoteBtn" class="btn btn-block btn-buy" style="display: <?= !$sellerCurrent ? 'block' : 'none' ?>">Yêu cầu báo giá</button>
        <?php if ($item->getLocalizeTotalPrice() > 3500000):?>
        <button type="button" id="installmentBtn" class="btn btn-block btn-installment">Thanh toán trả góp</button>
        <?php endif;?>
        <div class="text-center more">
            <a href="#" id="followItem" ><i class="icon fav"></i></a>
            <a href="#" id="addToCart"><i class="icon cart"></i></a>
        </div>
    </div>
<!--    <div class="payment-method-2">-->
<!--        <div class="title">Hình thức thanh toán</div>-->
<!--        <ul>-->
<!--            <li><img src="/img/detail_payment_1.png"></li>-->
<!--            <li><img src="/img/detail_payment_2.png"></li>-->
<!--            <li><img src="/img/detail_payment_3.png"></li>-->
<!--            <li><img src="/img/detail_payment_4.png"></li>-->
<!--            <li><img src="/img/detail_payment_5.png"></li>-->
<!--        </ul>-->
<!--    </div>-->
    <p>Sản phẩm dự kiến giao khoảng ngày <span class="text-orange"><?= date('d/m/Y',time()+(60*60*24*15)) ?></span> tới <span class="text-orange"><?= date('d/m/Y',time()+(60*60*24*30)) ?></span> nếu quý khách thanh toán trong hôm nay.</p>
    <div class="guaranteed">
        <div class="title">Đảm bảo khách hàng</div>
        <ul>
            <li><img src="/img/guaranteed_1.png"/> Yên tâm mua sắm</li>
            <li><img src="/img/guaranteed_2.png"/> Free ship toàn quốc</li>
            <li><img src="/img/guaranteed_3.png"/> Hỗ trợ đổi trả, khiếu nại</li>
            <li><img src="/img/guaranteed_4.png"/> Thủ tục trọn gói</li>
        </ul>
    </div>
    <div class="hotline">
        Hotline: <b class="text-orange">1900.6755</b>
    </div>
</div>

<?php
if(count($item->providers) > 1){?>
    <div class="border-block other-supplier">
        <div class="title">Nhà cung cấp khác</div>
        <ul>
            <?php foreach($item->providers as $k => $provider) {
                if ($provider->prov_id != $sellerCurrent) {
                    $temp = $item;
                    $temp->updateBySeller($provider->prov_id);
                    ?>
                    <li data-href="<?= $k > 1 ? 'more_seller' : '' ?>" style="display: <?= $k > 1 ? 'none' : 'block' ?>">
                        <b class="text-orange"><?= WeshopHelper::showMoney($temp->getLocalizeTotalPrice(), 1, '') ?>
                            <span class="currency">đ</span></b>
                        <div class="seller">Bán bởi: <span class="text-blue"><?= $provider->name ?></span></div>
                        <div class="rate text-orange">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <a href="<?= $url($provider->prov_id) ?>" target="_blank" class="btn btn-view">Xem ngay</a>
                    </li>
                <?php }
            }?>
            <li><a href="javascript:void (0)" onclick="viewMoreSeller(true)" style="display:block;" id="viewMoreSellerBtn" class="see-all text-blue">Xem tất cả <b><?= count($item->providers) -1 ?></b> người bán<i class="fas fa-caret-down"></i></a></li>
            <li><a href="javascript:void (0)" onclick="viewMoreSeller(false)" style="display:none;" id="HideSellerBtn" class="see-all text-blue">Ẩn bớt<i class="fas fa-caret-up"></i></a></li>
        </ul>
    </div>
<?php } ?>

<!--<div class="border-block related-product">-->
<!--    <div class="title">Sản phẩm tương tự</div>-->
<!--    <ul>-->
<!--        <li>-->
<!--            <a href="#">-->
<!--                <div class="thumb"><img src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG" alt=""/></div>-->
<!--                <div class="info">-->
<!--                    <div class="name">Bulova Mens Classic Sutton - 96A208</div>-->
<!--                    <div class="price">5.800.000<span class="currency">đ</span></div>-->
<!--                </div>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li>-->
<!--            <a href="#">-->
<!--                <div class="thumb"><img src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG" alt=""/></div>-->
<!--                <div class="info">-->
<!--                    <div class="name">Bulova Mens Classic Sutton - 96A208</div>-->
<!--                    <div class="price">5.800.000<span class="currency">đ</span></div>-->
<!--                </div>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li>-->
<!--            <a href="#">-->
<!--                <div class="thumb"><img src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG" alt=""/></div>-->
<!--                <div class="info">-->
<!--                    <div class="name">Bulova Mens Classic Sutton - 96A208</div>-->
<!--                    <div class="price">5.800.000<span class="currency">đ</span></div>-->
<!--                </div>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li><a href="#" class="see-all">Xem tất cả <i class="fas fa-caret-down"></i></a></li>-->
<!--    </ul>-->
<!--</div>-->
