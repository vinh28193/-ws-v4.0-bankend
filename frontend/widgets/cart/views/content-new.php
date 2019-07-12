<?php

use common\components\StoreManager;
use frontend\widgets\cart\CartWidgetHepper;
use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\View;

/** @var $this yii\web\View */
/** @var $items array */

/** @var  $storeManager  common\components\StoreManager */

$storeManager = Yii::$app->storeManager;
?>


<style type="text/css">
    .cart-body {
        font-size: 14px;
        font-weight: 500;
        letter-spacing: -0.1px;
        line-height: 24px;
    }

    .mtb-auto {
        margin-top: auto;
        margin-bottom: auto;
    }

    .cart-header, .cart-order, .cart-payment {
        background-color: #ffffff;
        border: 1px solid #efefef;
        margin-bottom: 1rem;
    }

    .cart-content {
        width: 100%;
        padding: 0;
    }

    .seller-info {
        border-bottom: 1px solid #efefef;
    }

    .fixed-header, .seller-info, .product-item, .cart-payment {
        padding: 1rem 0.50rem;
    }

    .fixed-header {

    }

    .product-list {
        padding-bottom: 0;
    }

    .product-item {
        border-bottom: 1px solid #efefef;
        margin: 0;
    }

    .product-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

</style>

<div class="cart-body">
    <div class="cart-header cart-border">
        <div class="fixed-header">
            <div class="row">
                <div class="col-md-4 col-sm-12 mtb-auto text-left">
                    <?= Yii::t('frontend', 'Product name'); ?>
                </div>
                <div class="col-md-1 col-sm-12 mtb-auto text-right">
                    <?= Yii::t('frontend', 'Price'); ?>
                </div>
                <div class="col-md-2 col-sm-12 mtb-auto text-center">
                    <?= Yii::t('frontend', 'Quantity'); ?>
                </div>
                <div class="col-md-2 col-sm-12 mtb-auto text-right">
                    <?= Yii::t('frontend', 'Tax/Domestic shipping'); ?>
                </div>
                <div class="col-md-1 col-sm-12 mtb-auto text-right">
                    <?= Yii::t('frontend', 'Purchase Fee'); ?>
                </div>
                <div class="col-md-2 col-sm-12 mtb-auto text-right">
                    <?= Yii::t('frontend', 'Total amount'); ?>
                </div>
            </div>
        </div>

    </div>
    <div class="cart-content cart-border">
        <?php foreach ($items as $item):
            $products = ArrayHelper::getValue($item, 'products', []);
            ?>
            <div class="cart-order" data-key="<?= $item['key'] ?>" data-type="shopping">
                <div class="seller-info">
                    <input class="form-check-input position-static source" name="checkCart"
                           style="margin: auto; height: 18px; width: 18px;" type="checkbox"
                           value="<?= $item['key'] ?>"
                           aria-label="..." <?= $item['selected'] ? 'checked' : ''; ?>>
                    <?= Yii::t('frontend', 'Sold by {seller} on {portal}', [
                        'seller' => Html::tag('span', $item['seller']['seller_name'], ['style' => 'color: #2b96b6;']),
                        'portal' => strtoupper($item['seller']['portal']) === 'EBAY' ? 'eBay' : Inflector::camelize(strtolower($item['seller']['portal']))
                    ]); ?>
                </div>
                <div class="product-list">
                    <?php foreach ($products as $product): ?>
                        <?php
                        $purchaseFee = 0;
                        if (isset($product['fees']['purchase_fee']) && $product['fees']['purchase_fee'] > 0) {
                            $purchaseFee = $product['fees']['purchase_fee'];
                        }
                        $availableQuantity = $product['available_quantity'];
                        $soldQuantity = $product['quantity_sold'];
                        $variations = ArrayHelper::getValue($product, 'variations');
                        if ($variations !== null) {
                            if (($variationSoldQuantity = ArrayHelper::getValue($variations, 'quantity_sold', $soldQuantity)) !== null && $variationSoldQuantity !== $soldQuantity) {
                                $soldQuantity = $variationSoldQuantity;
                            }
                            if (($variationAvailableQuantity = ArrayHelper::getValue($variations, 'available_quantity', $availableQuantity)) !== null && $variationAvailableQuantity !== $availableQuantity) {
                                $availableQuantity = $variationAvailableQuantity;
                            }
                        }
                        $availableQuantity = !($availableQuantity === null || (int)$availableQuantity < 0) ? $availableQuantity : 50;
                        $soldQuantity = !($soldQuantity === null || (int)$soldQuantity < 0) ? $soldQuantity : 0;
                        ?>
                        <div class="product-item">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 mtb-auto text-left">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 product-image">
                                            <img src="<?= $product['link_img']; ?>"
                                                 alt="<?= $product['product_name']; ?>"
                                                 style="width:95px;height: 80px"
                                                 title="<?= $product['product_name']; ?>">
                                        </div>
                                        <div class="col-md-9 col-sm-6 product-name">
                                            <div class="name">
                                                <?= $product['product_name']; ?>
                                                <a class="del delete-item ml-2" style="color: #2b96b6;"
                                                   data-parent="<?= $item['key'] ?>"
                                                   data-id="<?= $product['parent_sku'] ?>"
                                                   data-type="<?= $item['type'] ?>"
                                                   data-sku="<?= $product['sku']; ?>">
                                                    <i class="far fa-trash-alt"></i></a>
                                            </div>
                                            <!--                                            <div class="variant">-->
                                            <!--                                                Color:red;Size:2M-->
                                            <!--                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-12 mtb-auto text-right">
                                    <?= $storeManager->showMoney($product['total_unit_amount']); ?>
                                </div>
                                <div class="col-md-2 col-sm-12 mtb-auto text-center">
                                    <div class="qty form-inline quantity-option"
                                         style="width: 107px; height: 31px; border-radius: 3px; border: 1px solid #cecece; background-color: #ffffff; margin: auto">
                                        <div class="input-group" style="margin: auto">
                                            <div class="input-group-prepend">
                                                <button class="btn button-quantity-down"
                                                        data-pjax="1"
                                                        data-id="<?= $product['parent_sku'] ?>"
                                                        data-sku="<?= $product['sku']; ?>"
                                                        data-type="<?= $item['type'] ?>"
                                                        data-parent="<?= $item['key'] ?>"
                                                        data-operator="down"
                                                        type="button"><i class="la la-minus up-down"></i>
                                                </button>
                                            </div>
                                            <input type="text"
                                                   name="cartItemQuantity" class="form-control text-center"
                                                   style="border: none; border-left: 1px solid #cecece; border-right: 1px solid #cecece;max-height: 28.5px;"
                                                   value="<?= $product['quantity']; ?>"
                                                   data-min="1"
                                                   data-max="<?= (int)(($max = $availableQuantity - $soldQuantity) <= 0 ? 0 : $max); ?>"
                                                   data-parent="<?= $item['key'] ?>"
                                                   data-type="<?= $item['type'] ?>"
                                                   data-id="<?= $product['parent_sku'] ?>"
                                                   data-sku="<?= $product['sku']; ?>"
                                            >
                                            <div class="input-group-append">
                                                <button class="btn button-quantity-up"
                                                        data-pjax="1"
                                                        data-parent="<?= $item['key'] ?>"
                                                        data-id="<?= $product['parent_sku'] ?>"
                                                        data-type="<?= $item['type'] ?>"
                                                        data-sku="<?= $product['sku']; ?>"
                                                        data-operator="up"
                                                        type="button"><i class="la la-plus up-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12 mtb-auto text-right col-sm-12">
                                    <?= $storeManager->showMoney($product['total_us_fee']); ?>
                                </div>
                                <div class="col-md-1 col-sm-12 mtb-auto text-right col-sm-12 ">
                                    <?php
                                    echo $storeManager->showMoney($purchaseFee);
                                    ?>
                                </div>
                                <div class="col-md-2 col-sm-12 mtb-auto text-right final-amount">
                                    <?= $storeManager->showMoney($product['total_final_amount'] + $purchaseFee); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="cart-payment cart-border">
        <div class="row mt-2">
            <div class="col-md-12 text-right">
                <strong style="font-weight: 600;"><?= Yii::t('frontend', 'Total temporarily calculated amount'); ?></strong>
            </div>
            <div class="col-md-12 text-right mt-1">
                <span id="totalPrice" class="text-danger"
                      style="font-size: 24px; font-weight: 500;"><?= $storeManager->showMoney($totalAmount) ?></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 text-right">
                <button class="btn btn-link text-uppercase" onclick="ws.goback()">
                    <?php echo Yii::t('frontend', 'Continue shopping'); ?>
                </button>
                <button class="btn btn-amazon btn-lg text-uppercase" id="shoppingBtn"
                        style="float: right;margin-right: 5px;"><i
                            class="la la-shopping-cart"></i> <?= Yii::t('frontend', 'Make payment') ?></button>
            </div>
        </div>
    </div>
</div>