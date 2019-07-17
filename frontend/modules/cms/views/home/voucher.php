<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 16/08/2018
 * Time: 03:37 PM
 */
?>
<script>
    var data_voucher = <?= $data ?>;
</script>
<link href="/css/voucher.css" rel="stylesheet">
<script src="/js/voucher/jquery.formatCurrency.js"></script>
<script src="/js/voucher/voucher.js"></script>
<script src="/js/voucher/jquery.formatCurrency.all.js"></script>
<div class="container" style="margin-top: 50px">
    <span style="display: none" id="count_temp"></span>
    <div class="row">
        <?php
        for ($index = 1; $index < 121; $index++) {
            $title = strtoupper(substr(md5(microtime()), rand(0, 10), 5));
            $code = strtoupper(substr(md5(microtime()), rand(0, 10), 12));
            $amount = rand(100, 5000000);
            ?>
            <div class="col-md-3 col-sm-4" style="padding: 30px;text-align: center;">
                <div style="border: 1px solid #16a085;background-color: #ffffff">
                    <div class="pricing-table-header-tiny">
                        <h3><b style="font-size: 20px" id="title_voucher_<?= $index ?>">Voucher <?= $title ?></b>
                        </h3>
                    </div>
                    <div class="pricing-table-features">
                        <p><strong id="code_voucher_<?= $index ?>"><?= $code ?></strong>
                        <p>
                            <strong id="amount_voucher_<?= $index ?>"><?= strpos('VNĐ', $web->showMoney($amount)) ? $web->showMoney($amount) : $web->showMoney($amount) . " VNĐ" ?> </strong>
                    </div>
                    <div class="pricing-table-signup-tiny">
                        <p><a href="#"
                              onclick="voucher.detail(<?= $index ?>)"><?= \common\components\RedisLanguage::getLanguageByKey('buynow-voucher', 'Mua ngay!') ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        } ?>
    </div>
</div>
<script>
    voucher.loopChange();
</script>