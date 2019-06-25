<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var string $code */
/* @var frontend\modules\payment\Payment $payment */

?>

<style type="text/css">
    .center-box {
        text-align: center;
    }

    .button-group-fail {


    }

    .btn-continue {
        border-radius: 3px;
        border: 1px solid #d25e0d;
        background-image: linear-gradient(180deg, #ff9d17 0%, #e67424 100%);
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .btn-cancel {
        border-radius: 3px;
        border: 1px solid #2b96b6;
        background-image: linear-gradient(180deg, #2b96b6 0%, #2b96b6 100%);
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
    }
</style>
<div class="container">
    <div class="card card-checkout">
        <div class="card-body">
            <div class="center-box">
                <img src="/images/icon/payment_fail.png" alt="" title=""/>
                <p class="m-3">Đã có lỗi trong quá trình thanh toán hoặc giao dịch đã quá thời gian quy định. Bạn có
                    muốn tiến hành thanh toán lại hay không?</p>
                <div class="button-group">
                    <a href="#" class="btn btn-cancel" onclick="window.location.href = '/'">Huy bo</a>
                    <a href="#" class="btn btn-continue" onclick="window.location.href = '/'">Hinh Thuc thanh toan
                        khac</a>
                </div>

            </div>
        </div>
    </div>
</div>

