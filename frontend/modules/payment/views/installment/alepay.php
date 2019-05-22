<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var array $results */

echo "<pre>";
print_r($results);
echo "</pre>";

?>

<div class="installment-title">Bước 1: Chọn hình thức trả góp</div>
<ul class="method-list">
    <li><span class="active"><img src="./img/bank/vietcombank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/techcombank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/donga.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/mb.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/vib.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/viettin.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/eximbank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/acb.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/hdbank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/maritime.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/navibank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/vietabank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/vp.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/sacombank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/gpbank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/agribank.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/bidv.png" alt="" title=""/></span></li>
    <li><span><img src="./img/bank/oceanbank.png" alt="" title=""/></span></li>
</ul>
<div class="installment-title">Bước 2: Chọn loại thẻ thanh toán</div>
<ul class="method-list">
    <li><span class="active"><img src="/img/bank/jcb.png" alt="" title=""/></span></li>
    <li><span><img src="/img/bank/MASTERCARD.png" alt="" title=""/></span></li>
    <li><span><img src="/img/bank/VISA.png" alt="" title=""/></span></li>
</ul>
<div class="installment-title">Bước 3: Chọn số tiền muốn trả góp qua thẻ tín dụng</div>
<div class="form-group dropdown installment-select">
    <button class="btn dropdown-toggle" type="button" id="select-district" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">3.000.000đ
    </button>
    <div class="dropdown-menu" aria-labelledby="select-money">
        <a class="dropdown-item" href="#">3.000.000đ</a>
        <a class="dropdown-item" href="#">6.000.000đ</a>
        <a class="dropdown-item" href="#">9.000.000đ</a>
        <a class="dropdown-item" href="#">12.000.000đ</a>
    </div>
</div>
<div class="installment-title">Bước 4: Chọn số tháng trả góp</div>
<div class="installment-table">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>Thời hạn trả góp</td>
            <td class="text-blue">3 tháng</td>
            <td class="text-blue">6 tháng</td>
            <td class="text-blue">9 tháng</td>
            <td class="text-blue">12 tháng</td>
        </tr>
        <tr>
            <td>Giá Weshop</td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
        </tr>
        <tr>
            <td>Tiền trả hàng tháng</td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
        </tr>
        <tr>
            <td>Giá sau trả góp</td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
        </tr>
        <tr>
            <td>Giá chênh lệch</td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
            <td><b>7.399.000</b></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="" id="installment1" name="installment" checked>
                    <label class="form-check-label" for="installment1">Chọn</label>
                </div>
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="" id="installment2" name="installment">
                    <label class="form-check-label" for="installment2">Chọn</label>
                </div>
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="" id="installment3" name="installment">
                    <label class="form-check-label" for="installment3">Chọn</label>
                </div>
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="" id="installment4" name="installment">
                    <label class="form-check-label" for="installment4">Chọn</label>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
