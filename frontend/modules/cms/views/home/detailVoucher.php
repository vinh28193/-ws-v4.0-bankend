<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 17/08/2018
 * Time: 01:48 PM
 */

use common\components\RedisLanguage;

?>
<!--<link href="/v3/app/css/voucher.css" rel="stylesheet">-->
<script src="/v3/app/js/weshop/voucher.js"></script>

<style>
    .tiny {
        float: left;
        margin: 20px 0;
        background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAIklEQVQIW2NkQAIfP378zwjjgzj8/PyMYAEYB8RmROaABAAVMg/XkcvroQAAAABJRU5ErkJggg==) repeat;
        -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .2);
        -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .2);
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .2);
        border: 1px solid #14937a;
        border-bottom: 10px solid #14937a;
    }

    body {
        text-align: left;
    }

    .voucher {
        width: 45%;
        text-align: center;
    }

    .btn-a-voucher {
        display: inline-block;
        width: 50%;
        height: 50px;
        background: #16a085;
        line-height: 50px;
        color: #fff;
        text-decoration: none;
        text-transform: uppercase;
    }

    .pricing-table-header-tiny {
        padding: 5px 0 5px 0;
        background: #16a085;
        border-bottom: 10px solid #14937a;
    }

    .pricing-table-features {
        margin: 15px 10px 0 10px;
        padding: 0 10px 15px 10px;
        border-bottom: 1px dashed #888;
        text-align: center;
        line-height: 20px;
        font-size: 14px;
        color: #888;
    }
</style>
<div class="container">
    <div class="breadcrumb-new detail">
        <ul>
            <li>
                <a href="/voucher.html">
                    Voucher </a>
            </li>
        </ul>
    </div>
</div>
<div class="container">
    <!--Product info-->
    <div class="product-info-box ebay" style="overflow:visible;">
        <!-- Product Images Slide-->
        <div class="thumbnail-slider">
            <div class="big-image">
                <div class="magiczoomplus-example ">
                    <div class="main-example">
                        <div class="tiny voucher" style="margin: 25% 25%;">
                            <div class="pricing-table-header-tiny">
                                <h3><b style="font-size: 20px ;color: white" id="title_voucher"><?= $data['name'] ?></b>
                                </h3>
                            </div>
                            <div class="pricing-table-features">
                                <p><strong id="code_voucher"><?= $data['code'] ?></strong>
                                <p><strong id="amount_voucher"><?= $data['amount'] ?> </strong>
                            </div>
                            <div class="pricing-table-signup-tiny" style="padding-bottom: 25px;    margin-top: 25px">
                                <b class="btn-a-voucher"><?= RedisLanguage::getLanguageByKey('voucher-detail-img-btn', '-----') ?></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-info">
                <div class="left">
                    <?= RedisLanguage::getLanguageByKey('ebay-detail-itemid', 'Id:') ?>
                    <a href="" target="_blank" id="current_sku">
                        <?= $data['id'] ?>
                    </a>
                </div>

            </div>
            <div class="clearfix">

            </div>
        </div>
        <!-- End Product Images Slide-->

        <!-- Info-->
        <div class="product-info">
            <div class="top2">
                <div class="name"
                     id="item_name"><?= RedisLanguage::getLanguageByKey('voucher-detail-title', 'Voucher mua hàng Ebay') ?></div>
                <div class="seller">
                    <span><?= RedisLanguage::getLanguageByKey('detail-ebay-seller-title', 'Bán bởi'); ?></span>
                    <a href="#"><?= RedisLanguage::getLanguageByKey('voucher-detail-seller', 'Weshop Việt Nam') ?></a>
                </div>
            </div>

            <div class="content">
                <div class="row-cl clearfix">
                    <div class="row-left"><?= RedisLanguage::getLanguageByKey('ebay-detail-condition', 'Condition:') ?></div>
                    <div class="row-right">
                        <strong><?= RedisLanguage::getLanguageByKey('voucher-detail-condition', 'Voucher') ?></strong>
                        <i class="fa fa-question-circle"></i>
                    </div>
                </div>
                <div class="row-cl clearfix">
                    <div class="row-left"><?= RedisLanguage::getLanguageByKey('voucher-detail-code', 'Mã Voucher:') ?></div>
                    <div class="row-right">
                        <strong><?= $data['code'] ?></strong>
                    </div>
                </div>
                <div class="row-cl clearfix">
                    <div class="row-left"><?= RedisLanguage::getLanguageByKey('voucher-detail-amount', 'Giá trị:') ?></div>
                    <div class="row-right">
                        <strong><?= $data['amount'] ?></strong>
                    </div>
                </div>
                <div class="payment-box">
                    <div class="pm-1">
                        <div class="pm-col col-1">
                            <div>
                                <div class="detail open"><i class="see-more"></i>
                                    <strong>Giá bán:
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div class="pm-col col-2">
                            <input type="hidden" name="price_total" value="">
                            <div title="sell_price: <?= $data['amount'] ?>">
                                <strong class="price" id="price_local" title="Not round: <?= $data['amount'] ?>">
                                    <?php
                                    $amount = str_replace(' VNĐ', '', $data['amount']);
                                    $amount = str_replace('.', '', $amount);
                                    $amount = intval($amount) * 0.8;
                                    /** @var $web \common\models\weshop\Website */
                                    echo $web->showMoney($amount);
                                    ?> </strong>
                            </div>
                            <div id="percent-sale" style="display: block"><span
                                        class="old-price"><?=  $data['amount'] ?></span><strong
                                                                                       style="color: #d11e31;"> 20%
                                    off</strong></div>
                        </div>
                        <div class="pm-col col-3">
                            <!--#TODO: check button buynow with category-->
                            <button type="button" id="buyNow" onclick="voucher.buyNow('<?= $data['id'] ?>')"
                                    class="btn btn-default"
                                    style="    background: linear-gradient(#1078b8, #02539b); color: white">Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Info-->
    </div>
</div>
