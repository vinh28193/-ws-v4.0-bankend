<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 21/11/2018
 * Time: 09:43 AM
 */


$browser = \weshop\helpers\BrowserHepler::getBrowser();
if(in_array($browser['versionOS'],[4,5,6,7,8,9])&& $browser['platform'] == 'iPhone|iPad'){
    $iphoneOld = true;
}
?>

<li>
    <div class="lmkt-prod-detail">
        <div class="lmkt-prod-img">
            <a href="<?= $product->item_url ?>"
               class="lmkt-link-img">
                <img class="<?= $iphoneOld ? '' : 'lazy' ?>" <?= $iphoneOld ? 'src' : 'data-original' ?>="<?= $product->image ?>" alt="<?= $product->name ?>">
            </a>
            <?php if ($product->start_price != null && $product->start_price > $product->sell_price) { ?>
                <span class="lmkt-sale">-<?= round(100 * ($product->start_price - $product->sell_price) / $product->start_price) ?>
                    %</span>
            <?php } ?>
            <div class="lmkt-cart">
                <a href="<?= $product->item_url ?>">detail</a>
            </div>
        </div>
        <div class="lmkt-prod-tit">
            <a href="<?= $product->item_url ?>"><?= $product->name ?></a>
        </div>
        <div class="lmkt-prod-pri">
            <p>
                <?php
                echo $product->calculated_sell_price;
                if($product->calculated_start_price && $product->calculated_start_price > 0){
                    echo '<span>';
                    echo $product->calculated_start_price;
                    echo '</span>';
                }
                ?>

            </p>
        </div>
    </div>
</li>
