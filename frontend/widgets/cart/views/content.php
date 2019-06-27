<?php

use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $items array */

/** @var  $storeManager  common\components\StoreManager */

$storeManager = Yii::$app->storeManager;
?>

<?php if ($items) { ?>
    <div class="cart-box pr-3" id="load-card" style="border: none">
        <?php foreach ($items as $item):
            $products = ArrayHelper::getValue($item, 'products', []);
            ?>
            <div class="shadow-lg mb-3 cart-order" data-key="<?= $item['key'] ?>" data-type="shopping">
                <div class="row pt-4 pb-3 pl-2 pr-3 m-0" style="border-bottom: 1px solid silver">
                    <div class="col-md-7 seller">
                        <input class="form-check-input position-static source" name="checkCart"
                               style="margin: auto; height: 18px; width: 18px;" type="checkbox"
                               value="<?= $item['key'] ?>"
                               aria-label="..." <?= $item['selected'] ? 'checked' : ''; ?>>
                        <span>Nhà bán <a href="<?= $item['seller']['seller_link_store'] ?>"
                                         style="color: #2b96b6;"><?= $item['seller']['seller_name'] ?></a> trên <?= $item['seller']['portal'] ?></span>
                    </div>
                    <div class="col-md-5 summary text-right">
                        <span> <?= Yii::t('frontend', 'Total order amount'); ?></span> :
                        <span style="font-weight: 600"
                              class="text-danger"><?= $storeManager->showMoney($item['final_amount']) ?></span>
                    </div>
                </div>
                <div class="cart-header row hidden-md pt-2">
                    <div class="col-md-4"></div>
                    <div class="col-md-1 text-center"><?= Yii::t('frontend', 'Price'); ?></div>
                    <div class="col-md-2 text-center"><?= Yii::t('frontend', 'Quantity'); ?></div>
                    <div class="col-md-1 text-center"><?= Yii::t('frontend', 'Tax / Domestic shipping'); ?></div>
                    <div class="col-md-1 text-center"><?= Yii::t('frontend', 'Purchase Fee'); ?></div>
                    <div class="col-md-2 text-center"><?= Yii::t('frontend', 'International shipping fee'); ?></div>
                    <div class="col-md-1 text-center"><?= Yii::t('frontend', 'Total amount'); ?></div>
                </div>
                <div class="cart-item row pb-4">
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
                        ?>
                        <div class="col-md-12">
                            <div class="row" style="margin: 0">
                                <div class="col-md-1 col-sm-12 text-center pt-2" style="height: auto;">
                                    <img src="<?= $product['link_img']; ?>"
                                         alt="<?= $product['product_name']; ?>" width="80%" height="100px"
                                         title="<?= $product['product_name']; ?>">
                                </div>
                                <div class="col-md-3 col-sm-12 text-center pt-4">
                                    <a href="<?= $product['product_link']; ?>" target="_blank" class="name">
                                        <strong class="style-name"><?= $product['product_name']; ?></strong>
                                    </a>
                                    <a href="#" class="del delete-item ml-2" style="color: #2b96b6;"
                                       data-parent="<?= $item['key'] ?>"
                                       data-id="<?= $product['parent_sku'] ?>"
                                       data-type="<?= $item['type'] ?>"
                                       data-sku="<?= $product['sku']; ?>">
                                        <i class="far fa-trash-alt"></i> <?php echo Yii::t('frontend', 'Delete'); ?></a>
                                </div>
                                <div class="col-md-1 text-center col-sm-12 pt-4">
                                    <?= $storeManager->showMoney($product['total_unit_amount']); ?>
                                </div>
                                <div class="col-md-2 col-sm-12 text-center pt-4">
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
                                <div class="col-md-1 text-center col-sm-12 pt-4">
                                    <?= $storeManager->showMoney($product['total_us_fee']); ?>
                                </div>
                                <div class="col-md-1 text-center col-sm-12 pt-4">
                                    <?php
                                    $purchaseFee = 0;
                                    if (isset($product['fees']['purchase_fee']) && $product['fees']['purchase_fee'] > 0) {
                                        $purchaseFee = $product['fees']['purchase_fee'];
                                    }
                                    echo $storeManager->showMoney($purchaseFee);
                                    ?>
                                </div>

                                <div class="col-md-2 text-center col-sm-12 pt-4">
                                    <?php
                                    $internationalShipping = 0;
                                    if (isset($product['fees']['international_shipping_fee']) && $product['fees']['international_shipping_fee'] > 0) {
                                        $purchaseFee = $product['fees']['international_shipping_fee'];
                                    }
                                    echo $storeManager->showMoney($internationalShipping);
                                    ?>
                                </div>
                                <div class="col-md-1 text-center col-sm-12 pt-4">
                                    <div class="price price-option text-danger">
                                        <span style="font-weight: 600"><?= $storeManager->showMoney($product['total_final_amount']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="row mt-2">
            <div class="col-md-12 text-right">
                <strong style="font-weight: 600;"><?= Yii::t('frontend', 'Total temporarily calculated amount'); ?></strong>
            </div>
            <div class="col-md-12 text-right mt-1">
                <span id="totalPrice" class="text-danger"
                      style="font-size: 24px; font-weight: 500;"><?= $storeManager->showMoney($totalAmount) ?></span>
            </div>
        </div>
        <div class="row  mb-5 mt-2">
            <div class="col-md-12 text-right">
                <button class="btn btn-outline-info text-uppercase mt-2" style="text-transform: uppercase" onclick="ws.goback()">
                    <?php echo Yii::t('frontend', 'Continue shopping'); ?>
                </button>
                <button class="btn style-btn1 mt-2" id="shoppingBtn" style="text-transform: uppercase">
                    <span class="la la-shopping-cart float-left"
                          style="font-size: 1.6rem;"></span><?php echo Yii::t('frontend', 'Make payment'); ?>
                </button>
            </div>
        </div>
    </div>
<?php } ?>

