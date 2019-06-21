<?php

use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $items array */
$storeManager = Yii::$app->storeManager;
$totalAmount = 0;
?>

<div class="cart-box" id="load-card" style="border: none">
    <div class="row m-0" style="width: 100%">
        <div class="col-8"><strong>Sản phẩm</strong></div>
        <div class="col-2 text-center"><strong>Số Lượng</strong></div>
        <div class="col-2 text-right pr-1 text-right"><strong>Giá Tiền</strong></div>
    </div>
    <hr>
    <?php foreach ($items as $item):
            $products = ArrayHelper::getValue($item, 'products', []);

            ?>
            <div class="cart-item" data-key="<?= $item['key'] ?>" data-type="shopping"
                style="border-bottom: 1px solid #eeeeee">
                <?php foreach ($products as $product): ?>
                    <?php
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
                    $totalAmount += (int)$product['total_final_amount'];
                    ?>
                    <div class="row m-0 pb-2">
                        <div class="col-1 pb-2" style="height: auto;">
                            <img src="<?= $product['link_img']; ?>"
                                 alt="<?= $product['product_name']; ?>" width="80%" height="100px" title="<?= $product['product_name']; ?>">
                        </div>
                        <div class="col-7 pt-4">
                            <a href="<?= $product['product_link']; ?>" target="_blank" class="name">
                                <strong class="style-name"><?= $product['product_name']; ?></strong>
                            </a>
                            <a href="#" class="del delete-item"
                               data-parent="<?= $item['key'] ?>"
                               data-id="<?= $product['parent_sku'] ?>"
                               data-type="<?= $item['type'] ?>"
                               data-sku="<?= $product['sku']; ?>"
                            <i class="far fa-trash-alt"></i> Xóa</a>
                        </div>
                        <div class="col-2 pt-4 text-center">
                            <div class="qty form-inline quantity-option" style="width: 107px; height: 31px; border-radius: 3px; border: 1px solid #cecece; background-color: #ffffff; margin: auto">
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
                                           style="border: none; border-left: 1px solid #cecece; border-right: 1px solid #cecece"
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
                        <div class="col-2 pt-4 text-right pr-1">
                            <div class="price price-option text-danger">
                                <span style="font-weight: 600"><?= $storeManager->showMoney($product['total_final_amount']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <div class="row mt-2">
        <div class="col-12 text-right">
            <strong style="font-weight: 600;">Tổng số tiền tạm tính</strong><br>
            <span id="total" class="text-danger" style="font-size: 24px; font-weight: 500"><?= $storeManager->showMoney($totalAmount)?></span>
        </div>
    </div>
    <div class="row text-right mb-5 mt-2">
        <div class="col-12">
            <button class="btn style-btn mt-2" id="installmentBtn">
                <span class="la la-credit-card float-left" style="font-size: 1.7em;"></span>MUA TRẢ GÓP
            </button>
            <button class="btn style-btn1 mt-2 " id="shoppingBtn">
                <span class="la la-shopping-cart float-left" style="font-size: 1.7em;"></span>THỰC HIỆN ĐẶT MUA
            </button>
        </div>
    </div>
</div>

