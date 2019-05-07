<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var array $products */
?>
<div class="search-content search-2">
    <div class="title-box">
        <div class="left">
            <div class="text">Tìm kiếm “Bulova” từ</div>
            <img src="/img/logo_ebay.png" alt=""/>
            <span>Hiển thị 1-48 của 1.000 kết quả.</span>
        </div>
        <div class="right">
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Sắp xếp theo
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" type="button">Action</button>
                    <button class="dropdown-item" type="button">Another action</button>
                    <button class="dropdown-item" type="button">Something else here</button>
                </div>
            </div>
            <ul class="control-page">
                <li><a href="#" class="control prev"></a></li>
                <li><a href="#" class="control next"></a></li>
            </ul>
        </div>
    </div>

    <div class="product-list row">
        <?php
        foreach ($products as $product) {
            echo $this->render('_item', [
                'product' => $product
            ]);
        }
        ?>
    </div>
</div>

<nav aria-label="...">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true"></a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item active" aria-current="page">
            <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
        </li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><span class="more">...</span></li>
        <li class="page-item"><a class="page-link last" href="#">20</a></li>
        <li class="page-item">
            <a class="page-link" href="#"></a>
        </li>
    </ul>
</nav>