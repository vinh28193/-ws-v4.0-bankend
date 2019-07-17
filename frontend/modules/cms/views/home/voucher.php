<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 16/08/2018
 * Time: 03:37 PM
 */
/**
 * @var \yii\web\View $this
 */
$this->registerJsFile("/js/voucher/jquery.formatCurrency.js",['position' => \yii\web\View::POS_END]);
$this->registerJsFile("/js/voucher/voucher.js",['position' => \yii\web\View::POS_END]);
$this->registerJsFile("/js/voucher/jquery.formatCurrency.all.js",['position' => \yii\web\View::POS_END]);
$this->registerCssFile("/css/voucher.css");
?>
<script>
    var data_voucher = <?= json_encode($data) ?>;
</script>
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
                        <p><a href="javascript: void(0);"
                              onclick="voucher.detail(<?= $index ?>)"><?= Yii::t('frontend','Buy now!')?></a>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        } ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        voucher.loopChange();
    });
</script>