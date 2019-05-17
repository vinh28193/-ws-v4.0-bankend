<?php

use frontend\widgets\alias\AliasWidget;
use frontend\assets\FancyboxPlusAsset;
use frontend\widgets\search\SearchBoxWidget;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $portal string */

$this->beginContent('@frontend/views/layouts/common.php');
FancyboxPlusAsset::register($this);
$js = <<<JS
$(document).ready(function () {
        $('.slick-slider').slick({
            infinite: true,
            vertical: true,
            arrows: true,
            prevArrow: $('.slider-prev'),
            nextArrow: $('.slider-next'),
            slidesToShow: 5
        });

        $('#detail-big-img').ezPlus({
            imageCrossfade: true,
            easing: true,
            scrollZoom: true,
            cursor: 'zoom-in',
            gallery: 'detail-slider',
            galleryActiveClass: 'active'
        });
    });
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>
    <div class="keep-navbar <?= strtolower($portal) == 'amazon-jp' ? 'amazon' : strtolower($portal) ?> other-page">
        <div class="container">
            <?php
            echo AliasWidget::widget(['type' => $portal]);
            echo SearchBoxWidget::widget([])
            ?>
        </div>
    </div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/">Weshop Global</a></li>
                <li class="breadcrumb-item"><a href="/<?= $portal?>.html">Shop Amazon</a></li>
                <li class="breadcrumb-item active">Tìm kiếm từ khóa <?= Yii::$app->request->get('keyword','') ?></li>
            </ol>
        </nav>
        <?= $content; ?>
<!--        <div class="product-viewed product-list">-->
<!--            <div class="title">Sản phẩm đã xem:</div>-->
<!--            <div id="product-viewed" class="owl-carousel owl-theme">-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                        <span class="sale-tag">30% OFF</span>-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="item-box">-->
<!--                    <a href="#" class="item">-->
<!--                        <div class="thumb">-->
<!--                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"-->
<!--                                 alt="" title=""/>-->
<!--                        </div>-->
<!--                        <div class="info">-->
<!--                            <div class="rate text-orange">-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star"></i>-->
<!--                                <i class="fas fa-star-half-alt"></i>-->
<!--                                <i class="far fa-star"></i>-->
<!--                                <span>(87)</span>-->
<!--                            </div>-->
<!--                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>-->
<!--                            <div class="price">-->
<!--                                <strong>20.430.000</strong>-->
<!--                                <span>6.800.000</span>-->
<!--                            </div>-->
<!--                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>

<?php

$this->endContent();
