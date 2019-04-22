<?php

use frontend\assets\ItemAsset;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */

ItemAsset::register($this);

Pjax::begin([
    'options' => [
        'id' => 'ebay-item',
        'class' => 'detail-content'
    ]
]);

?>
<div class="row">
    <div class="col-md-9">
        <div class="detail-block">
            <div class="thumb-slider">
                <div class="detail-slider">
                    <i class="fas fa-chevron-up slider-prev"></i>
                    <i class="fas fa-chevron-down slider-next"></i>
                    <div id="gal1 detail-slider" class="slick-slider">
                        <div class="item">
                            <a class="elevatezoom-gallery"
                               data-image="https://i.ebayimg.com/00/s/MTIwMFgxMjAw/z/ez0AAOSwkjJcR2i2/$_1.JPG"
                               data-zoom-image="https://i.ebayimg.com/00/s/MTIwMFgxMjAw/z/ez0AAOSwkjJcR2i2/$_1.JPG">
                                <img src="https://i.ebayimg.com/00/s/MTIwMFgxMjAw/z/ez0AAOSwkjJcR2i2/$_1.JPG" width="100"/>
                            </a>
                        </div>
                        <div class="item">
                            <a class="elevatezoom-gallery"
                               data-image="https://i.ebayimg.com/00/s/Nzk2WDc5Ng==/z/xIgAAOSwwPRcRj6Z/$_1.JPG"
                               data-zoom-image="https://i.ebayimg.com/00/s/Nzk2WDc5Ng==/z/xIgAAOSwwPRcRj6Z/$_1.JPG">
                                <img src="https://i.ebayimg.com/00/s/Nzk2WDc5Ng==/z/xIgAAOSwwPRcRj6Z/$_1.JPG" width="100"/>
                            </a>
                        </div>
                        <div class="item">
                            <a class="elevatezoom-gallery"
                               data-image="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.6/demo/images/small/image3.jpg"
                               data-zoom-image="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.6/demo/images/large/image3.jpg">
                                <img src="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.6/demo/images/small/image3.jpg" width="100"/>
                            </a>
                        </div>
                        <div class="item">
                            <a class="elevatezoom-gallery"
                               data-image="https://i.ebayimg.com/00/s/MTI4MFgxMjgw/z/cR8AAOSwAdFcWfvC/$_1.JPG"
                               data-zoom-image="https://i.ebayimg.com/00/s/MTI4MFgxMjgw/z/cR8AAOSwAdFcWfvC/$_1.JPG">
                                <img src="https://i.ebayimg.com/00/s/MTI4MFgxMjgw/z/cR8AAOSwAdFcWfvC/$_1.JPG" width="100"/>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#" class="elevatezoom-gallery"
                               data-image="https://i.ebayimg.com/00/s/MTAwMFgxMjI2/z/IOwAAOSwhr9cLgep/$_12.JPG"
                               data-zoom-image="https://i.ebayimg.com/00/s/MTAwMFgxMjI2/z/IOwAAOSwhr9cLgep/$_12.JPG">
                                <img src="https://i.ebayimg.com/00/s/MTAwMFgxMjI2/z/IOwAAOSwhr9cLgep/$_12.JPG" width="100"/>
                            </a>
                        </div>
                        <div class="item">
                            <a class="elevatezoom-gallery"
                               data-image="https://i.ebayimg.com/00/s/MTI4MFgxMjgw/z/cR8AAOSwAdFcWfvC/$_1.JPG"
                               data-zoom-image="https://i.ebayimg.com/00/s/MTI4MFgxMjgw/z/cR8AAOSwAdFcWfvC/$_1.JPG">
                                <img src="https://i.ebayimg.com/00/s/MTI4MFgxMjgw/z/cR8AAOSwAdFcWfvC/$_1.JPG" width="100"/>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#" class="elevatezoom-gallery"
                               data-image="https://i.ebayimg.com/00/s/MTAwMFgxMjI2/z/IOwAAOSwhr9cLgep/$_12.JPG"
                               data-zoom-image="https://i.ebayimg.com/00/s/MTAwMFgxMjI2/z/IOwAAOSwhr9cLgep/$_12.JPG">
                                <img src="https://i.ebayimg.com/00/s/MTAwMFgxMjI2/z/IOwAAOSwhr9cLgep/$_12.JPG" width="100"/>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="big-img">
                    <img id="detail-big-img" class="detail-big-img"
                         src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG"
                         data-zoom-image="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG"/>
                    <div class="out-of-date">
                        <span><i class="fas fa-exclamation-triangle"></i><br/>Hàng hết hạn bán</span>
                    </div>
                </div>
            </div>
            <div class="product-full-info">
                <a href="#" class="brand">Bulova</a>
                <div class="title">
                    <h2>Women's Bugi BUR223GN Diamond Flower Engraved Gold Tone Stainless Steel Watch</h2>
                    <span class="sale-tag">30% OFF</span>
                </div>
                <div class="rating">
                    <div class="rate text-orange">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span>87 người đánh giá</span>
                </div>
                <div class="price">
                    <strong class="text-orange">5.600.000<span class="currency">đ</span></strong>
                    <b class="old-price">9.800.000<span class="currency">đ</span></b>
                    <span class="save">(Tiết kiệm: 814.000đ)</span>
                </div>
                <div class="total-price">(Giá trọn gói về Việt Nam với trọng lượng ước tính <span>450 gram</span>)</div>
                <div class="option-box form-inline">
                    <label>Chọn size:</label>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">M</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">S</a>
                            <a class="dropdown-item" href="#">M</a>
                            <a class="dropdown-item" href="#">L</a>
                        </div>
                    </div>
                    <label>Tình trạng: Hàng mới</label>
                </div>
                <div class="option-box">
                    <label>Màu sắc: màu đỏ</label>
                    <ul class="style-list">
                        <li class="active"><span><img src="https://i.ebayimg.com/00/s/MTA3N1gxNjAw/z/p0EAAOSwVcZb1ovR/$_57.JPG" alt=""/></span></li>
                        <li><span><img src="https://i.ebayimg.com/00/s/MTI5MVgxNjAw/z/qsUAAOSwR6Bb1oxl/$_57.JPG" alt=""/></span></li>
                        <li><span><img src="https://i.ebayimg.com/00/s/OTg2WDE2MDA=/z/syUAAOSwIaRb1oxr/$_57.JPG" alt=""/></span></li>
                    </ul>
                </div>
                <ul class="info-list">
                    <li>Imported</li>
                    <li>Due to a recent redesign by Bulova, recently manufactured Bulova watches,including all watches sold and shipped by Amazon, will not feature the Bulova tuning fork logo on the watch face.</li>
                    <li>Brown patterned and rose gold dial</li>
                    <li>Leather strap,</li>
                    <li>Slightly domed mineral crystal</li>
                    <li>Water resistant to 99 feet (30 M): withstands rain and splashes of water, but not showering or submersion</li>
                    <li>Case Diameter: 42 mm ; Case Thickness: 11.2 mm ; 3-year Limited Warranty</li>
                </ul>
                <a href="#" class="more text-blue">Xem thêm <i class="fas fa-caret-down"></i></a>
            </div>
        </div>
        <div class="product-viewed product-list">
            <div class="title">Sản phảm liên quan:</div>
            <div id="product-viewed-2" class="owl-carousel owl-theme">
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                        <span class="sale-tag">30% OFF</span>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="detail-block-2">
            <div class="title">Mô tả sản phẩm:</div>
            <ul>
                <li>Brushed and polished stainless steel case measures 42mm diameter by 13mm thick.</li>
                <li>Genuine leather strap includes a special deployment clasp.</li>
                <li>Patterned brown dial has classic rose gold tone luminous hands.</li>
                <li>Roman numeral hour markers make this handsome watch absolutely stunning.</li>
                <li>Skeleton sub dials allow you to see the wonders of the precise Automatic movement within the case.</li>
                <li>Twenty-four hour and seconds sub dials.</li>
                <li>The scratch resistant mineral crystal protects the water resistant case.</li>
            </ul>
        </div>
    </div>
    <div class="col-md-3">
        <div class="border-block payment-info">
            <div class="qty form-inline">
                <label>Số lượng:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button">-</button>
                    </div>
                    <input type="text" class="form-control" value="1"/>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">+</button>
                    </div>
                </div>
            </div>
            <div class="action-box">
                <button type="button" class="btn btn-block btn-buy">Mua ngay</button>
                <button type="button" class="btn btn-block btn-installment">Thanh toán trả góp</button>
                <div class="text-center more">
                    <a href="#"><i class="icon fav"></i></a>
                    <a href="#"><i class="icon cart"></i></a>
                </div>
            </div>
            <div class="payment-method-2">
                <div class="title">Hình thức thanh toán</div>
                <ul>
                    <li><img src="/img/detail_payment_1.png"></li>
                    <li><img src="/img/detail_payment_2.png"></li>
                    <li><img src="/img/detail_payment_3.png"></li>
                    <li><img src="/img/detail_payment_4.png"></li>
                    <li><img src="/img/detail_payment_5.png"></li>
                </ul>
            </div>
            <p>Sản phẩm dự kiến giao khoảng ngày <span class="text-orange">28/02/2019</span> tới <span class="text-orange">07/03/2019</span> nếu quý khách thanh toán trong hôm nay.</p>
            <div class="guaranteed">
                <div class="title">Đảm bảo khách hàng</div>
                <ul>
                    <li><img src="/img/guaranteed_1.png"/> Yên tâm mua sắm</li>
                    <li><img src="/img/guaranteed_2.png"/> Free ship toàn quốc</li>
                    <li><img src="/img/guaranteed_3.png"/> Hỗ trợ đổi trả, khiếu nại</li>
                    <li><img src="/img/guaranteed_4.png"/> Thủ tục trọn gói</li>
                </ul>
            </div>
            <div class="hotline">
                Hotline: <b class="text-orange">1900.6755</b>
            </div>
        </div>
        <div class="border-block other-supplier">
            <div class="title">Nhà cung cấp khác</div>
            <ul>
                <li>
                    <b class="text-orange">50.800.000<span class="currency">đ</span></b>
                    <div class="seller">Bán bởi: <span class="text-blue">Multiple supplier</span></div>
                    <div class="rate text-orange">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <button type="button" class="btn btn-view">Xem ngay</button>
                </li>
                <li>
                    <b class="text-orange">50.800.000<span class="currency">đ</span></b>
                    <div class="seller">Bán bởi: <span class="text-blue">Multiple supplier</span></div>
                    <div class="rate text-orange">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <button type="button" class="btn btn-view">Xem ngay</button>
                </li>
                <li><a href="#" class="see-all text-blue">Xem tất cả <i class="fas fa-caret-down"></i></a></li>
            </ul>
        </div>
        <div class="border-block related-product">
            <div class="title">Sản phẩm tương tự</div>
            <ul>
                <li>
                    <a href="#">
                        <div class="thumb"><img src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG" alt=""/></div>
                        <div class="info">
                            <div class="name">Bulova Mens Classic Sutton - 96A208</div>
                            <div class="price">5.800.000<span class="currency">đ</span></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="thumb"><img src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG" alt=""/></div>
                        <div class="info">
                            <div class="name">Bulova Mens Classic Sutton - 96A208</div>
                            <div class="price">5.800.000<span class="currency">đ</span></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="thumb"><img src="https://i.ebayimg.com/00/s/OTkxWDEwMzY=/z/AnMAAOSwJGlbTSId/$_57.JPG" alt=""/></div>
                        <div class="info">
                            <div class="name">Bulova Mens Classic Sutton - 96A208</div>
                            <div class="price">5.800.000<span class="currency">đ</span></div>
                        </div>
                    </a>
                </li>
                <li><a href="#" class="see-all">Xem tất cả <i class="fas fa-caret-down"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<?php Pjax::end();?>
<div class="detail-block-2">
    <div class="row">
        <div class="col-md-3">
            <div class="title">Chi tiết sản phẩm:</div>
        </div>
        <div class="col-md-6">
            <div class="detail-table">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td>Brand, Seller, or Collection Name</td>
                        <td>Brand, Seller, or Collection Name</td>
                    </tr>
                    <tr>
                        <td>Model number</td>
                        <td>Model number</td>
                    </tr>
                    <tr>
                        <td>Brand, Seller, or Collection Name</td>
                        <td>Brand, Seller, or Collection Name</td>
                    </tr>
                    <tr>
                        <td>Model number</td>
                        <td>Model number</td>
                    </tr>
                    <tr>
                        <td>Brand, Seller, or Collection Name</td>
                        <td>Brand, Seller, or Collection Name</td>
                    </tr>
                    <tr>
                        <td>Model number</td>
                        <td>Model number</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
